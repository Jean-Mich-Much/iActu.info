<?php declare(strict_types=1);
include_once 'vision/vision.php';

ignore_user_abort(true);

$logDir = __DIR__.'/Fondation/logs';
if (!is_dir($logDir)) {
    @mkdir($logDir, 0775, true);
}
$logPath = $logDir.'/papoter.log';
$BnMp = 'fyRpua';
$cleanFile = $logDir.'/.clean_day';
$today = date('Ymd');

function papoter_finish_and_respond(array $data): void
{
    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    if (!headers_sent()) {
        header('Content-Type: application/json');
    }
    echo json_encode($data, JSON_THROW_ON_ERROR);

    if (ob_get_level() > 0) {
        ob_end_flush();
    }

    if (function_exists('fastcgi_finish_request')) {
        fastcgi_finish_request();
    } else {
        flush();
    }
}

function papoter_log(string $message, string $path): void
{
    $entry = sprintf("[%s] %s\n", (new DateTime())->format('Y-m-d H:i:s.u'), $message);
    @file_put_contents($path, $entry, FILE_APPEND | LOCK_EX);
}

function papoter_transform_links(string $text): string
{
    $regex = '/(?<!["\'>])(https?:\/\/[^\s<]+)/i';

    return preg_replace_callback($regex, function ($matches) {
        $url = $matches[1];
        $url = str_replace('http://', 'https://', $url);
        $urlHash = hash('xxh64', $url);
        $title = '';
        $cache = Vision_API::find(fn ($c) => ($c['data']['type'] ?? '') === 'chat_link_cache' && ($c['data']['hash'] ?? '') === $urlHash)->current();

        if ($cache) {
            $title = $cache['data']['title'];
        } else {
            $domainPart = 'Lien';
            $domain = parse_url($url, PHP_URL_HOST) ?: '';
            $cleanDomain = preg_replace('/^www\./i', '', $domain);
            $domainPart = explode('.', $cleanDomain)[0];
            $wave = Vision::wave();
            if (($wave['charge'] ?? 0) < 0.8 && ($wave['pression'] ?? '') !== 'high') {
                $context = stream_context_create([
                    'http' => [
                        'timeout' => 1.5,
                        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) iActu-Bot/1.0\r\n",
                    ],
                ]);
                $content = @file_get_contents($url, false, $context, 0, 16384);
                if ($content && preg_match('/<title[^>]*>(.*?)<\/title>/is', $content, $t)) {
                    $title = trim(strip_tags($t[1]));
                }

                if ($title) {
                    Vision_API::create(['type' => 'chat_link_cache', 'hash' => $urlHash, 'title' => $title]);
                }
            }
        }

        if (!$title) {
            $title = isset($domainPart) ? ucfirst($domainPart) : 'Lien';
        }
        $displayTitle = '🔗 '.$title;
        if (mb_strlen($displayTitle) > 64) {
            $displayTitle = mb_substr($displayTitle, 0, 61).'...';
        }
        $linkId = 'n'.substr(hash('xxh64', $url), 0, 8).random_int(1, 999);

        return sprintf(
            '<a class="chat-lien" rel="noopener" target="_blank" id="%s" href="%s">%s</a>',
            $linkId,
            htmlspecialchars($url, ENT_QUOTES),
            htmlspecialchars($displayTitle, ENT_QUOTES)
        );
    }, $text);
}

function papoter_clean_html(string $html): string
{
    return preg_replace_callback('/<(\/?)([a-z1-6]+)([^>]*)>/i', function ($m) {
        $allowed = ['b', 'i', 'u', 's', 'span', 'div', 'br', 'p', 'font', 'strong', 'em', 'strike', 'a'];
        $tag = strtolower($m[2]);
        if (!in_array($tag, $allowed)) {
            return '';
        }
        if ($m[1] === '/') {
            return "</$tag>";
        }
        preg_match_all('/(class|style|color|size|align|rel|target|id|href)\s*=\s*["\']([^"\']*)["\']/i', $m[3], $attrMatches, PREG_SET_ORDER);
        $attrs = '';
        foreach ($attrMatches as $am) {
            $val = $am[2];
            if (!preg_match('/javascript:|on\w+|data:|&|\\\/i', $val)) {
                $attrs .= " {$am[1]}=\"".htmlspecialchars($val, ENT_QUOTES).'"';
            }
        }

        return "<$tag".(empty($attrs) ? '' : $attrs).'>';
    }, $html);
}

function papoter_conditional_clean(): void
{
    if (random_int(1, 100) === 1) {
        Vision_API::clean();
    }
}

function papoter_get_next_id_val(): int
{
    $lastIdCell = Vision_API::find(fn ($c) => ($c['data']['type'] ?? '') === 'chat_last_id')->current();
    if (!$lastIdCell) {
        $max = 1;
        foreach (Vision_API::find(fn ($c) => ($c['data']['type'] ?? '') === 'chat_user' && isset($c['data']['short_id'])) as $u) {
            $idVal = (int) str_replace('#', '', $u['data']['short_id']);
            if ($idVal > $max) {
                $max = $idVal;
            }
        }
        Vision_API::create(['type' => 'chat_last_id', 'last_val' => $max]);

        return $max + 1;
    }

    return (int) $lastIdCell['data']['last_val'] + 1;
}

function papoter_sync_last_id(): void
{
    $lastIdCell = Vision_API::find(fn ($c) => ($c['data']['type'] ?? '') === 'chat_last_id')->current();
    $max = 1;
    foreach (Vision_API::find(fn ($c) => ($c['data']['type'] ?? '') === 'chat_user' && isset($c['data']['short_id'])) as $u) {
        $idVal = (int) str_replace('#', '', $u['data']['short_id']);
        if ($idVal > $max) {
            $max = $idVal;
        }
    }
    if ($lastIdCell) {
        $data = $lastIdCell['data'];
        $data['last_val'] = $max;
        Vision_API::update($lastIdCell['id'], $data);
    } else {
        Vision_API::create(['type' => 'chat_last_id', 'last_val' => $max]);
    }
}

function papoter_run_background_maintenance(string $logPath, string $cleanFile, string $today): void
{
    $maintenanceFiber = new Fiber(function () use ($logPath, $cleanFile, $today) {
        if (random_int(1, 250) === 1 || @file_get_contents($cleanFile) !== $today) {
            if (file_exists($logPath) && stat($logPath)['size'] > 32768) {
                file_put_contents($logPath, '');
                Fiber::suspend('log_rotated');
            }

            $msgLimit = time() - 7776000;
            $cacheLimit = time() - 2592000;
            $toDelete = Vision_API::find(fn ($c) => (($c['data']['type'] ?? '') === 'chat_msg' && ($c['createdAt'] ?? 0) < $msgLimit)
                                                 || (($c['data']['type'] ?? '') === 'chat_link_cache' && ($c['createdAt'] ?? 0) < $cacheLimit));

            $count = 0;
            foreach ($toDelete as $cell) {
                Vision_API::delete($cell['id']);
                ++$count;

                if ($count % 25 === 0 || Vision::wave()['ram'] === 'critical') {
                    Fiber::suspend('deletion_batch_pause');
                }
                usleep(1000);
            }

            Vision_API::clean();
            file_put_contents($cleanFile, $today);
            Fiber::suspend('io_cleanup_done');
        }

        if (random_int(1, 125) === 1) {
            papoter_sync_last_id();
            Fiber::suspend('sync_done');
        }

        papoter_conditional_clean();
    });

    try {
        $maintenanceFiber->start();

        while ($maintenanceFiber->isSuspended()) {
            $wave = Vision::wave();
            $backoff = ($wave['ram'] === 'critical' || ($wave['charge'] ?? 0) > 0.8) ? 150000 : 20000;
            usleep($backoff);

            if ($maintenanceFiber->isSuspended()) {
                $maintenanceFiber->resume();
            }
        }
    } catch (Throwable) {
    }
}

$PAgI = 'hdAyJo';
$userFingerprint = hash('xxh64', $_SERVER['REMOTE_ADDR'].($_SERVER['HTTP_USER_AGENT'] ?? 'unknown'));
$uid = $_COOKIE['papoter_uid'] ?? '';
$currentCell = ($uid !== '') ? Vision_API::read($uid) : null;
if (!$currentCell) {
    $currentCell = Vision_API::find(fn ($c) => ($c['data']['type'] ?? '') === 'chat_user' && ($c['data']['hash'] ?? '') === $userFingerprint)->current();
    if ($currentCell) {
        setcookie('papoter_uid', $currentCell['id'], time() + 31536000, '/', '', true, true);
    }
}

$configCell = Vision_API::find(fn ($c) => ($c['data']['type'] ?? '') === 'chat_config')->current();
$MM2oeqsJUHNk = (is_array($configCell) && isset($configCell['data']['T60HrzwU'])) ? $configCell['data']['T60HrzwU'] : $BnMp.$PAgI;
$isAdmin = false;
$isModo = false;
$isBanned = false;
$banMsg = '';
if (!$currentCell) {
    do {
        $pseudo = 'Bob '.bin2hex(random_bytes(2));
        $exists = Vision_API::find(fn ($c) => ($c['data']['type'] ?? '') === 'chat_user' && ($c['data']['pseudo'] ?? '') === $pseudo)->current();
    } while ($exists || strtolower($pseudo) === 'iactu');

    $userData = ['type' => 'chat_user', 'hash' => $userFingerprint, 'pseudo' => $pseudo, 'emoji' => '😊', 'ban_count' => 0, 'last_ban_at' => 0, 'ip' => $_SERVER['REMOTE_ADDR']];
    $userId = Vision_API::create($userData);
    setcookie('papoter_uid', $userId, time() + 31536000, '/', '', true, true);
    $currentCell = ['id' => $userId, 'data' => $userData];
    $currentPseudo = $pseudo;
    $currentEmoji = '😊';
    $userHistory = [];
    $shortId = null;
    $lastMsgTime = 0;
} else {
    $currentPseudo = $currentCell['data']['pseudo'];
    $currentEmoji = $currentCell['data']['emoji'] ?? '😊';
    $shortId = $currentCell['data']['short_id'] ?? null;
    $userId = $currentCell['id'];
    $userHistory = $currentCell['data']['msg_history'] ?? [];
    $lastMsgTime = $currentCell['data']['last_msg_t'] ?? 0;
    $isAdmin = (bool) ($currentCell['data']['is_admin'] ?? false);
    $isModo = (bool) ($currentCell['data']['is_modo'] ?? false);
    $isBanned = ($currentCell['data']['banned_until'] ?? 0) > time();
    if ($isAdmin) {
        $isBanned = false;
    }
    $banMsg = '';
    if ($isBanned) {
        $diff = $currentCell['data']['banned_until'] - time();
        $h = floor($diff / 3600);
        $m = (int) floor(($diff % 3600) / 60);
        if ($h >= 48) {
            $d = (int) floor($h / 24);
            $banMsg = "Vous êtes banni. Temps restant : {$d} jours.";
        } else {
            $banMsg = "Vous êtes banni. Temps restant : {$h}h {$m}min.";
        }
    }
}

if (isset($_GET['action']) && $isBanned && in_array($_GET['action'], ['send', 'edit_msg', 'reserve_id', 'update_pseudo', 'update_msg_subject', 'toggle_pin', 'delete_msg', 'ban_user'])) {
    papoter_finish_and_respond(['status' => 'error', 'msg' => $banMsg]);
    exit;
}

$allowedSubjects = ['Papoter', 'Technologies', 'Apple', 'Jeux', 'Sciences', 'Actualités'];
function normalize_subject(?string $s, array $allowed): string
{
    return (in_array($s, $allowed, true)) ? $s : 'Papoter';
}  if (isset($_GET['action'])) {
    if ($_GET['action'] === 'get_messages') {
        $fS = $_GET['subject'] ?? '';
        $search = $_GET['search'] ?? '';
        $lastSync = $_GET['sync'] ?? '';
        $force = isset($_GET['force']);
        $start = time();
        $currentSync = '';
        if ($currentCell && (time() - ($currentCell['data']['last_seen'] ?? 0)) > 1) {
            $currentCell['data']['last_seen'] = time();
            Vision_API::update($currentCell['id'], $currentCell['data']);
        }

        $presenceThreshold = time() - 12;

        $getUserStatesHash = function () use ($presenceThreshold) {
            $states = [];
            $now = time();
            foreach (Vision_API::find(fn ($c) => ($c['data']['type'] ?? '') === 'chat_user') as $u) {
                if (($u['data']['last_seen'] ?? 0) > $presenceThreshold) {
                    $states[] = 'c:'.$u['data']['pseudo'].($u['data']['emoji'] ?? '');
                }
            }
            sort($states);

            return hash('xxh64', implode('|', $states));
        };

        clearstatcache();
        $stats = Vision_API::stats();
        $logStat = file_exists($logPath) ? filemtime($logPath).filesize($logPath) : '';
        $currentSync = hash('xxh64', ($stats['total'] ?? 0).'|'.($stats['count'] ?? 0).'|'.$logStat.'|'.$getUserStatesHash());

        if (!$force && $search === '' && $lastSync !== '' && $currentSync === $lastSync) {
            while (time() - $start < 5) {
                usleep(200000);
                clearstatcache();
                $stats = Vision_API::stats();
                $logStat = file_exists($logPath) ? filemtime($logPath).filesize($logPath) : '';
                $currentSync = hash('xxh64', ($stats['total'] ?? 0).'|'.($stats['count'] ?? 0).'|'.$logStat.'|'.$getUserStatesHash());
                if ($currentSync !== $lastSync) {
                    break;
                }
            }
            if ($currentSync === $lastSync) {
                papoter_finish_and_respond(['no_change' => true, 'sync' => $currentSync]);
                exit;
            }
            $currentCell = Vision_API::read($userId);
            if ($currentCell) {
                $isAdmin = (bool) ($currentCell['data']['is_admin'] ?? false);
                $isModo = (bool) ($currentCell['data']['is_modo'] ?? false);
            }
        }

        $all = [];
        $filter = fn ($c) => ($c['data']['type'] ?? '') === 'chat_msg'
            && ($fS === '' || ($c['data']['subject'] ?? 'Papoter') === $fS)
            && ($search === '' || mb_stripos(strip_tags($c['data']['text'] ?? ''), $search) !== false);
        foreach (Vision_API::find($filter) as $m) {
            $all[] = $m;
            if (count($all) > 200) {
                break;
            }
        }
        usort($all, function ($a, $b) {
            $pinA = (bool) ($a['data']['is_pinned'] ?? false);
            $pinB = (bool) ($b['data']['is_pinned'] ?? false);
            if ($pinA !== $pinB) {
                return $pinB <=> $pinA;
            }

            return $b['createdAt'] - $a['createdAt'];
        });
        $lastMessages = array_slice($all, 0, 40);
        foreach ($lastMessages as &$m) {
            if (!isset($m['data']['subject']) || !in_array($m['data']['subject'], $allowedSubjects, true) || $m['data']['subject'] === 'Discussions') {
                $m['data']['subject'] = normalize_subject($m['data']['subject'] ?? null, $allowedSubjects);
                Vision_API::update($m['id'], $m['data']);
            }
        }
        unset($m);

        $connected = [];
        foreach (Vision_API::find(fn ($c) => ($c['data']['type'] ?? '') === 'chat_user' && ($c['data']['last_seen'] ?? 0) > $presenceThreshold) as $u) {
            $connected[] = [
                'pseudo' => $u['data']['pseudo'],
                'emoji' => $u['data']['emoji'] ?? '😊',
                'isAdmin' => (bool) ($u['data']['is_admin'] ?? false),
                'short_id' => $u['data']['short_id'] ?? null,
                'isModo' => (bool) ($u['data']['is_modo'] ?? false),
            ];
        }

        papoter_finish_and_respond([
            'messages' => $lastMessages,
            'user' => ['isAdmin' => $isAdmin, 'isModo' => $isModo, 'isBanned' => $isBanned, 'banMsg' => $banMsg, 'shortId' => $shortId, 'nextId' => '#'.papoter_get_next_id_val()],
            'connected' => $connected,
            'sync' => $currentSync,
        ]);
        exit;
    }
    if ($_GET['action'] === 'send' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['valid_email_check'])) {
            echo json_encode(['status' => 'error', 'msg' => 'Bot détecté (H)'], JSON_THROW_ON_ERROR);
            exit;
        }
        $loadTime = (int) ($_POST['ts_load'] ?? 0);
        $pow = $_POST['pow'] ?? '';
        if (!hash_equals(strrev(base64_encode($userFingerprint)), $pow)) {
            echo json_encode(['status' => 'error', 'msg' => 'Vérification de sécurité échouée.'], JSON_THROW_ON_ERROR);
            exit;
        }
        if ((time() - $lastMsgTime) < 2 && !$isAdmin && !$isModo) {
            echo json_encode(['status' => 'error', 'msg' => 'Trop rapide ! (2s min)'], JSON_THROW_ON_ERROR);
            exit;
        }
        $now = time();
        $userHistory = array_filter($userHistory, fn ($t) => $t > ($now - 60));
        if (count($userHistory) >= 8 && !$isAdmin && !$isModo) {
            $userData = array_merge($currentCell['data'], [
                'banned_until' => $now + 7200,
                'last_ban_at' => $now,
            ]);
            Vision_API::update($userId, $userData);
            echo json_encode(['status' => 'error', 'msg' => 'Sécurité anti-spam : Oups ! Vous papotez un peu trop vite pour le serveur. Une petite pause de 2 heures s\'impose pour laisser respirer le chat ! 😊 Plus de 8 messages par minute...'], JSON_THROW_ON_ERROR);
            exit;
        }
        $rawText = $_POST['text'] ?? '';
        $text = papoter_clean_html($rawText);
        $text = papoter_transform_links($text);
        $text = mb_substr(trim($text), 0, 8192);
        if (preg_match('/^~(modo|revoke)#(.*)~$/', strip_tags($text), $m)) {
            if ($isAdmin) {
                $cmd = $m[1];
                $tId = '#'.trim($m[2], '# ');
                if (ctype_digit(str_replace('#', '', $tId)) && $tId !== '#1') {
                    $target = Vision_API::find(fn ($c) => ($c['data']['type'] ?? '') === 'chat_user' && ($c['data']['short_id'] ?? '') === $tId)->current();
                    if ($target) {
                        $td = $target['data'];
                        $td['is_modo'] = ($cmd === 'modo');
                        Vision_API::update($target['id'], $td);
                    }
                }
            }
            papoter_finish_and_respond(['status' => 'ok']);
            exit;
        }

        if (strip_tags($text) === $MM2oeqsJUHNk) {
            if ($isAdmin && $shortId === '#1') {
                papoter_finish_and_respond(['status' => 'ok']);
                exit;
            }
            $userData = array_merge($currentCell['data'], [
                'pseudo' => 'iActu',
                'emoji' => '🚀',
                'is_admin' => true,
                'is_modo' => false,
                'short_id' => '#1',
                'last_msg_t' => $now,
            ]);
            Vision_API::update($userId, $userData);
            foreach (Vision_API::find(fn ($m) => ($m['data']['type'] ?? '') === 'chat_msg' && ($m['data']['user_hash'] ?? '') === $userFingerprint) as $msg) {
                $md = $msg['data'];
                $md['short_id'] = '#1';
                $md['pseudo'] = 'iActu';
                $md['emoji'] = '🚀';
                Vision_API::update($msg['id'], $md);
            }
            usleep(20000);
            papoter_finish_and_respond(['status' => 'admin_promoted']);
            papoter_run_background_maintenance($logPath, $cleanFile, $today);
            exit;
        }
        if ($isAdmin && mb_strpos($text, 'Plan🚀B:') === 0) {
            $newCmd = ltrim(trim(mb_substr($text, 7)), ':');
            if (mb_strlen($newCmd) >= 4) {
                $configData = ['type' => 'chat_config', 'T60HrzwU' => $newCmd];
                if ($configCell) {
                    Vision_API::update($configCell['id'], $configData);
                } else {
                    Vision_API::create($configData);
                }   $userData = $currentCell['data'];
                $userData['is_admin'] = true;
                Vision_API::update($userId, $userData);
                usleep(20000);
                papoter_finish_and_respond(['status' => 'admin_promoted', 'msg' => 'Commande mise à jour ! Vous restez administrateur.']);
                papoter_run_background_maintenance($logPath, $cleanFile, $today);
                exit;
            }
        }
        if ($text !== '') {
            $subject = $_POST['subject'] ?? 'Papoter';
            if ($subject === '') {
                $subject = 'Papoter';
            }
            $msgPseudo = preg_replace('/\s*#\d+$/', '', $currentPseudo);
            $parentId = $_POST['parent_id'] ?? null;
            $parentPseudo = $_POST['parent_pseudo'] ?? null;
            $relations = [];
            if ($parentId && Vision_API::exists($parentId)) {
                $relations[] = ['id' => $parentId, 'niveau' => 1];
            }
            Vision_API::create(['type' => 'chat_msg', 'pseudo' => $msgPseudo, 'emoji' => $currentEmoji, 'text' => $text, 'user_hash' => $userFingerprint, 'short_id' => $shortId, 'ip' => $_SERVER['REMOTE_ADDR'], 'subject' => $subject, 'parent_id' => $parentId, 'parent_pseudo' => $parentPseudo], $relations);
            $userHistory[] = $now;
            $userData = array_merge($currentCell['data'], ['msg_history' => $userHistory, 'last_msg_t' => $now]);
            Vision_API::update($userId, $userData);
            usleep(10000);
            papoter_finish_and_respond(['status' => 'ok']);
            papoter_run_background_maintenance($logPath, $cleanFile, $today);
        }
        exit;
    }
    if ($_GET['action'] === 'reserve_id' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($currentCell && !isset($currentCell['data']['short_id'])) {
            $newIdVal = papoter_get_next_id_val();
            $shortId = '#'.$newIdVal;
            $currentCell['data']['short_id'] = $shortId;
            Vision_API::update($currentCell['id'], $currentCell['data']);
            $lastIdCell = Vision_API::find(fn ($c) => ($c['data']['type'] ?? '') === 'chat_last_id')->current();
            if ($lastIdCell) {
                $ld = $lastIdCell['data'];
                $ld['last_val'] = $newIdVal;
                Vision_API::update($lastIdCell['id'], $ld);
            }
            $myOldMsgs = Vision_API::find(fn ($m) => ($m['data']['type'] ?? '') === 'chat_msg' && ($m['data']['user_hash'] ?? '') === $userFingerprint);
            foreach ($myOldMsgs as $msg) {
                $md = $msg['data'];
                $md['short_id'] = $shortId;
                Vision_API::update($msg['id'], $md);
            }

            papoter_finish_and_respond(['status' => 'ok', 'shortId' => $shortId]);
            exit;
        }
        papoter_finish_and_respond(['status' => 'error']);
        exit;
    }
    if ($_GET['action'] === 'edit_msg' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $msgId = $_POST['msg_id'] ?? '';
        $newText = $_POST['text'] ?? '';
        if ($msgId && Vision_API::exists($msgId) && ($msg = Vision_API::read($msgId)) && ($msg['data']['type'] ?? '') === 'chat_msg') {
            $owned = ($shortId && ($msg['data']['short_id'] ?? '') === $shortId) || (($msg['data']['user_hash'] ?? '') === $userFingerprint);
            $isTargetAdmin = ($msg['data']['short_id'] ?? '') === '#1' || (bool) ($msg['data']['is_admin'] ?? false);
            if ($owned || $isAdmin || ($isModo && !$isTargetAdmin)) {
                $text = papoter_clean_html($newText);
                $text = papoter_transform_links($text);
                $text = mb_substr(trim($text), 0, 8192);
                $data = $msg['data'];
                $data['text'] = $text;
                Vision_API::update($msgId, $data);
                papoter_run_background_maintenance($logPath, $cleanFile, $today);
                papoter_finish_and_respond(['status' => 'ok']);
                exit;
            }
        }
        exit;
    }
    if ($_GET['action'] === 'update_msg_subject' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $msgId = $_POST['msg_id'] ?? '';
        $msg = Vision_API::read($msgId);
        $newSub = normalize_subject($_POST['subject'] ?? 'Papoter', $allowedSubjects);
        if ($msg && ($msg['data']['type'] ?? '') === 'chat_msg') {
            $owned = ($shortId && ($msg['data']['short_id'] ?? '') === $shortId) || (($msg['data']['user_hash'] ?? '') === $userFingerprint);
            $isTargetAdmin = ($msg['data']['short_id'] ?? '') === '#1' || (bool) ($msg['data']['is_admin'] ?? false);
            if ($owned || $isAdmin || ($isModo && !$isTargetAdmin)) {
                $data = $msg['data'];
                $data['subject'] = $newSub;
                Vision_API::update($msgId, $data);
                papoter_run_background_maintenance($logPath, $cleanFile, $today);
                papoter_finish_and_respond(['status' => 'ok']);
            }
        }
        exit;
    }
    if ($_GET['action'] === 'update_pseudo' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $inputPseudo = trim($_POST['pseudo'] ?? '');

        if (str_starts_with($inputPseudo, '#')) {
            if ($inputPseudo === '#1' && !$isAdmin) {
                echo json_encode(['status' => 'error', 'msg' => 'Identifiant #1 réservé à l\'administration.'], JSON_THROW_ON_ERROR);
                exit;
            }
            $targetUser = Vision_API::find(fn ($c) => ($c['data']['type'] ?? '') === 'chat_user' && ($c['data']['short_id'] ?? '') === $inputPseudo)->current();
            if ($targetUser) {
                $data = $targetUser['data'];
                $data['hash'] = $userFingerprint;
                $data['ip'] = $_SERVER['REMOTE_ADDR'];
                Vision_API::update($targetUser['id'], $data);
                foreach (Vision_API::find(fn ($m) => ($m['data']['type'] ?? '') === 'chat_msg' && ($m['data']['user_hash'] ?? '') === $userFingerprint && !isset($m['data']['short_id'])) as $msg) {
                    $md = $msg['data'];
                    $md['short_id'] = $inputPseudo;
                    Vision_API::update($msg['id'], $md);
                }

                setcookie('papoter_uid', $targetUser['id'], time() + 31536000, '/', '', true, true);
                papoter_finish_and_respond(['status' => 'ok', 'reload' => true]);
                exit;
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'Identifiant inconnu'], JSON_THROW_ON_ERROR);
                exit;
            }
        }

        $newPseudo = mb_substr($inputPseudo, 0, 16);
        if (strtolower($newPseudo) === 'iactu' && !$isAdmin) {
            echo json_encode(['status' => 'error', 'msg' => 'Pseudo réservé !'], JSON_THROW_ON_ERROR);
            exit;
        }
        $newEmoji = mb_substr(trim($_POST['emoji'] ?? $currentEmoji), 0, 32);
        if (mb_strlen($newPseudo) >= 4 && mb_strlen($newPseudo) <= 16) {
            $isTaken = Vision_API::find(fn ($c) => ($c['data']['type'] ?? '') === 'chat_user'
                && mb_strtolower($c['data']['pseudo'] ?? '') === mb_strtolower($newPseudo)
                && $c['id'] !== $userId
            )->current();
            if ($isTaken) {
                echo json_encode(['status' => 'error', 'msg' => 'Désolé, ce pseudo est déjà pris !'], JSON_THROW_ON_ERROR);
                exit;
            }
            $userData = array_merge($currentCell['data'], ['pseudo' => $newPseudo, 'emoji' => $newEmoji]);
            Vision_API::update($userId, $userData);
            usleep(20000);
            papoter_finish_and_respond(['status' => 'ok', 'new' => $newPseudo]);
            papoter_run_background_maintenance($logPath, $cleanFile, $today);
        } else {
            echo json_encode(['status' => 'error', 'msg' => '4-16 caractères requis'], JSON_THROW_ON_ERROR);
        }
        exit;
    }
    if ($isAdmin && $_GET['action'] === 'toggle_pin' && isset($_GET['msg_id'])) {
        if (Vision_API::exists($_GET['msg_id']) && ($msg = Vision_API::read($_GET['msg_id'])) && ($msg['data']['type'] ?? '') === 'chat_msg') {
            $data = $msg['data'];
            $data['is_pinned'] = !($data['is_pinned'] ?? false);
            Vision_API::update($msg['id'], $data);
            papoter_run_background_maintenance($logPath, $cleanFile, $today);
            papoter_finish_and_respond(['status' => 'ok']);
        } else {
            papoter_finish_and_respond(['status' => 'error']);
        }
        exit;
    }

    if (isset($_GET['msg_id'])) {
        $msgId = $_GET['msg_id'];
        if ($_GET['action'] === 'delete_msg' && Vision_API::exists($msgId) && ($targetMsg = Vision_API::read($msgId))) {
            $owned = ($shortId && ($targetMsg['data']['short_id'] ?? '') === $shortId) || (($targetMsg['data']['user_hash'] ?? '') === $userFingerprint);
            $isTargetAdmin = ($targetMsg['data']['short_id'] ?? '') === '#1' || (bool) ($targetMsg['data']['is_admin'] ?? false);
            if ($owned || $isAdmin || ($isModo && !$isTargetAdmin)) {
                Vision_API::delete($msgId);
                usleep(20000);
                papoter_finish_and_respond(['status' => 'ok']);
                papoter_run_background_maintenance($logPath, $cleanFile, $today);
                exit;
            }
        } if (($isAdmin || $isModo) && $_GET['action'] === 'ban_user' && Vision_API::exists($msgId) && ($targetMsg = Vision_API::read($msgId))) {
            $targetIp = $targetMsg['data']['ip'] ?? 'inconnue';
            $targetHash = $targetMsg['data']['user_hash'] ?? null;
            if (!$targetHash) {
                echo json_encode(['status' => 'error', 'msg' => 'Utilisateur introuvable'], JSON_THROW_ON_ERROR);
                exit;
            } $now = time();
            $todayStart = strtotime('today midnight');
            $targetShortId = $targetMsg['data']['short_id'] ?? null;
            $users = iterator_to_array(Vision_API::find(fn ($c) => ($c['data']['type'] ?? '') === 'chat_user' && (
                ($c['data']['hash'] ?? '') === $targetHash
                || ($targetShortId && ($c['data']['short_id'] ?? '') === $targetShortId)
            )
            ));
            $banned = false;
            $isMajorBan = false;
            foreach ($users as $u) {
                if (($u['data']['is_admin'] ?? false) || ($u['data']['short_id'] ?? '') === '#1') {
                    echo json_encode(['status' => 'error', 'msg' => 'Action impossible sur un admin.'], JSON_THROW_ON_ERROR);
                    exit;
                } $uData = $u['data'];
                $lastBan = $uData['last_ban_at'] ?? 0;
                $banCount = $uData['ban_count'] ?? 0;
                $banCount = (($now - $lastBan) < 172800) ? ($banCount + 1) : 1;
                $isMajorBan = ($banCount >= 3);
                $uData['banned_until'] = $now + ($isMajorBan ? 2592000 : 86400);
                $uData['last_ban_at'] = $now;
                $uData['ban_count'] = $isMajorBan ? 0 : $banCount;
                Vision_API::update($u['id'], $uData);
                usleep(10000);
                $banned = true;
            } if ($banned) {
                $msgsToDelete = Vision_API::find(fn ($m) => ($m['data']['type'] ?? '') === 'chat_msg' && ($m['data']['user_hash'] ?? '') === $targetHash && ($isMajorBan || ($m['createdAt'] ?? 0) >= $todayStart));
                foreach ($msgsToDelete as $mt) {
                    Vision_API::delete($mt['id']);
                    usleep(2000);
                } usleep(50000);
                papoter_finish_and_respond(['status' => 'ok']);
            }
            papoter_run_background_maintenance($logPath, $cleanFile, $today);
            exit;
        } echo json_encode(['status' => 'error', 'msg' => 'Action ou message introuvable'], JSON_THROW_ON_ERROR);
        exit;
    }
}
ob_start();
?>
<!DOCTYPE html>
<html lang="fr">

 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Papoter</title>
  <style>
  <?php $css = @file_get_contents(__DIR__.'/Fondation/css/papoter.css');
echo $css ?: '';
?>
  </style>
  <script>
  const IS_ADMIN = <?php echo $isAdmin ? 'true' : 'false'; ?>;
  const IS_MODO = <?php echo $isModo ? 'true' : 'false'; ?>;
  const BAN_MSG = <?php echo json_encode($banMsg); ?>;
  </script>
 </head>

 <body>
  <main class="chat-shell">
   <header class="chat-header">
    <div class="menu-left"> <a id="menu-logo" class="logo" href="index.php" title=" Accueil ">
      <div class="logo-part logo-ico">🔖</div>
      <div class="logo-part logo-iACTU">iACTU</div>
      <div class="logo-part logo-barre">|</div>
      <div class="logo-part logo-INFO">INFO</div>
      <div class="logo-part logo-rocket">🚀</div>
     </a> </div>
    <div class="user-config">
     <div class="emoji-selector-wrapper">
      <div id="selected-emoji" class="current-emoji-display"><?php echo htmlspecialchars($currentEmoji); ?></div>
      <div class="emoji-dropdown">
       <div class="emoji-popover skeuo-elevated">
        <div class="emoji-grid"> <?php $primary = ['😊', '😎', '🧐', '🤓', '🤖', '🐱', '🦊', '🐦', '🍀', '🔥', '✨', '🌈', '🍕', '🍺', '🎸', '🕹️', '👾', '👻', '🎃', '🎄', '🎁', '🏆', '⚽', '🏀'];
$extra = ['🚲', '🚗', '✈️', '🌍', '🌙', '☀️', '💎', '💡', '📖', '✏️', '🔑', '🔒', '❤️', '🤘', '👍', '👋', '🤳', '🧠', '🍄', '🍦', '🍹', '🎬', '🎤', '🎈', '🎨', '🧪', '🔭', '🛡️', '⚔️', '🏹', '🔱', '⚓', '🛸', '🪐', '🌟', '⛈️', '❄️', '🌊', '🌋', '🏔️', '🏕️', '⛺', '🏠', '🏢', '🏰', '🗼', '🗽', '🗺️', '⌚', '📱', '💻', '📷', '🔍', '🔔', '📣', '📦', '✉️', '📈', '📅', '📌', '📎', '🗑️', '🛠️', '🔩', '⚖️', '🧨', '🩹', '🩺', '🩸', '💊', '🧼', '🪟', '🫠', '🫡', '🫢', '🫣', '🥱', '🤔', '🤨', '😐', '😑', '😶', '😏', '😒', '🙄', '😬', '🤥', '😌', '😴', '🤒', '🤕', '🤢', '🤮', '🤧', '🥵', '🥶', '🥴', '😵', '🤯', '🤠', '🥳', '🥸', '🫤', '😟', '🥺', '🥹', '😱', '😤', '😡', '🤬', '💀', '☠️', '💩', '🤡', '👽', '🦾', '🐾', '🍓', '🥥', '🥨', '🍔', '🍟', '🧁', '🥑', '🥦', '🥓'];
foreach ($primary as $e) { ?> <span class="emoji-option" onclick="selectEmoji('<?php echo $e; ?>')"><?php echo $e; ?></span> <?php } ?> </div>
        <div class="emoji-more-container"> <button type="button" id="toggle-extra-btn" class="more-btn" onclick="toggleExtraEmojis(event)">+ d'émojis</button> </div>
        <div id="extra-emojis-grid" class="emoji-grid extra-emojis-hidden"> <?php foreach ($extra as $e) { ?> <span class="emoji-option" onclick="selectEmoji('<?php echo $e; ?>')"><?php echo $e; ?></span> <?php } ?> </div>
       </div>
      </div>
     </div>
     <div class="pseudo-input-wrapper">
      <input type="text" id="pseudo-input" value="<?php echo htmlspecialchars($currentPseudo); ?>" maxlength="30">
      <span id="pseudo-hint" class="pseudo-hint <?php echo $shortId ? 'active-id' : ''; ?>" style="<?php echo $shortId ? 'display:block;opacity:1;' : ''; ?>">
       <?php echo $shortId ?: 'Coller ID ou changer pseudo ou emoji'; ?>
      </span>
     </div>
     <input type="hidden" id="emoji-value" value="<?php echo htmlspecialchars($currentEmoji); ?>">
     <button id="update-pseudo" class="btn-skeuo" title="Mettre à jour le profil">Modifier</button>
    </div> <?php $jours = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
$mois = ['', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
$now = new DateTime();
$j = $jours[$now->format('w')];
$d = $now->format('j');
$m = $mois[$now->format('n')];
$txt_date = "$j $d $m"; ?> <div class="menu-right"> <span id="menu-date">📅 <?php echo $txt_date; ?></span> </div>
   </header>
   <section class="chat-editor-zone">
    <div class="hp-wrap"> <input type="text" name="valid_email_check" tabindex="-1" autocomplete="off"> </div>
    <div class="editor-toolbar"> <button type="button" class="tool-btn" onclick="format('removeFormat')" title="Défaut">🧹</button>
     <div class="tool-sep"></div> <button type="button" class="tool-btn" onclick="format('fontSize', '3')" title="16px">A</button> <button type="button" class="tool-btn" onclick="format('fontSize', '5')" title="20px">A+</button> <button type="button" class="tool-btn" onclick="format('fontSize', '7')" title="24px">A++</button>
     <div class="tool-sep"></div> <button type="button" class="tool-btn" onclick="format('normalText')" title="Caractère Normal">N</button> <button type="button" class="tool-btn" onclick="format('bold')" title="Gras"><b>G</b></button> <button type="button" class="tool-btn" onclick="format('italic')" title="Italique"><i>I</i></button> <button type="button" class="tool-btn" onclick="format('underline')" title="Souligné"><u>S</u></button> <button type="button" class="tool-btn" onclick="format('strikeThrough')" title="Barré"><s>B</s></button>
     <div class="tool-sep"></div> <button type="button" class="tool-btn color-r" onclick="format('foreColor', '#cc0022')">●</button> <button type="button" class="tool-btn color-g" onclick="format('foreColor', '#007722')">●</button> <button type="button" class="tool-btn color-b" onclick="format('foreColor', '#0055fc')">●</button>
     <div class="tool-sep"></div> <button type="button" class="tool-btn" onclick="format('justifyLeft')" title="Aligner à gauche">
      <div class="align-icon align-left"><span></span><span></span><span></span></div>
     </button> <button type="button" class="tool-btn" onclick="format('justifyCenter')" title="Centrer">
      <div class="align-icon align-center"><span></span><span></span><span></span></div>
     </button> <button type="button" class="tool-btn" onclick="format('justifyRight')" title="Aligner à droite">
      <div class="align-icon align-right"><span></span><span></span><span></span></div>
     </button>
    </div>
    <div class="editor-info-line">
     <div id="char-counter" class="char-limit-info">8192 caractères restants</div>
     <div id="id-display-zone" class="id-display-zone"></div>
    </div>
    <div id="reply-info" class="reply-info"></div>
    <div class="input-wrapper skeuo-inset">
     <div id="chat-input" contenteditable="true" data-placeholder="Écrire un message... 💬"></div>
     <button id="cancel-edit-btn" class="cancel-edit-btn" style="display:none">🗙 Annuler</button> <button id="send-btn" class="send-trigger"> <svg viewBox="0 0 24 24" width="24" height="24">
       <path fill="currentColor" d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
      </svg> </button>
    </div> <input type="hidden" id="fp-ref" value="<?php echo $userFingerprint; ?>">
   </section>
   <div class="chat-body">
    <button id="toggle-sidebar" class="sidebar-toggle-btn" title="Afficher/Masquer le panneau">»</button>
    <section id="messages-container" class="messages-area"> </section>
    <aside class="chat-sidebar">
     <div class="sidebar-section">
      <h3>😀 Connectés</h3>
      <div id="connected-list"></div>
     </div>
     <div class="sidebar-section">
      <h3>📚 Catégories</h3>
      <div id="subjects-list"></div>
     </div>
    </aside>
   </div>
  </main>
  <script>
  <?php

    $js = @file_get_contents(__DIR__.'/Fondation/js/papoter.js');
echo $js ?: '';
?>
  </script>
 </body>

</html>
<?php

$html_final = ob_get_clean();

echo preg_replace('/ {2,}/', ' ', $html_final);

if (ob_get_level() > 0) {
    ob_end_flush();
}
