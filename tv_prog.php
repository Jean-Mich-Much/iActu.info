<?php
$titre_page = 'Tv';
$nom_page = 'tv_prog';
$nom_page_theme_alternatif = 'theme_02_tv.php';
$page_active = 'tv';
$page_tv_active= 'programme';
$titre_page_active = '&#129302;&nbsp;Tv';
$cache =30;
$theme = '01';

if (!file_exists($nom_page . '.html') || filemtime($nom_page . '.html')< (time() - $cache) || !file_exists($nom_page . date("j") . '.html')) {
 ob_start(); ?>

<!DOCTYPE html>
<html lang="fr">

<head>
<?php @include "Structure/php/modules/header.php"; ?>
<style type="text/css">
<?php @readfile("Structure/css/fonts.css");
@readfile("Structure/css/base.css");
@readfile("Structure/css/". $theme . ".css");
?>

.tvchaine {
 align-self: stretch;
 display: flex;
 align-content: start;
 align-items: stretch;
 justify-content: start;
 justify-items: stretch;
 border-bottom: round(up, calc(1 / 1920 * 100vw), 1px) solid var(--c7);
 padding-bottom: round(up, calc(12 / 1920 * 100vw), 6px);
 padding-top: 0;
 padding-left: round(up, calc(8 / 1920 * 100vw), 4px);
 padding-right: round(up, calc(8 / 1920 * 100vw), 4px);
 color: var(--c10);
 font-variation-settings: "wght" 600;
 overflow: hidden;
}

.tvprog {
  display: flex;
  flex-direction: column;
  align-content: start;
  align-items: stretch;
  justify-content: start;
  justify-items: stretch;
  background-color: var(--c8);
  color: var(--c2);
  font-synthesis-small-caps: auto;
  font-synthesis-weight: auto;
  font-synthesis: style;
  font-variant-emoji: emoji;
  font-variant: discretionary-ligatures tabular-nums;
  outline: round(up, calc(4 / 1920 * 100vw), 2px) solid var(--c7);
  outline-offset: round(up, calc(-4 / 1920 * 100vw), 2px);
  border-radius: round(up, calc(12 / 1920 * 100vw), 6px);
  padding: round(up, calc(16 / 1920 * 100vw), 6px);
  margin-bottom: round(up, calc(8 / 1920 * 100vw), 4px);
  overflow: hidden;
}

.tvprogitem {
  display: flex;
  justify-content: stretch;
  justify-items: start;
  align-content: center;
  align-items: center;
  padding: round(up, calc(8 / 1920 * 100vw), 4px);
  border-bottom: round(up, calc(1 / 1920 * 100vw), 1px) solid var(--c7);
  flex-wrap: nowrap;
  white-space: nowrap;
  overflow: hidden;
}

.tvprogheure {
  display: inline-flex;
}

.tvprogtitle {
  display: inline-flex;
  flex: 1;
  flex-wrap: nowrap;
  white-space: nowrap;
  overflow: hidden;
}

.tvprogduree {
  display: inline-flex;
}

@media (max-width: 719px) {
  .tvprogduree {
    display: none;
  }
}
</style>
</head>

<body id="body" lang="fr">
<div class="flex-page">
<div class="menu"><?php @include "Structure/php/modules/menu.php"; ?></div>
<div class="menu"><?php @include "Structure/php/modules/menu_tv.php"; ?></div>
<?php @include 'Structure/php/modules/messages_top.php'; ?>

<div class="mid">
<?php try {
$xmlFile = "Structure/cache/tv/xmltv_tnt.xml";
$xml = new DOMDocument();
@$xml->load($xmlFile) or die();
$xp = new DOMXPath($xml);
$chs = $xp->query("/tv/channel");
$now = new DateTime("now", new DateTimeZone("Europe/Paris"));
$shown = [];
$yesterday = (clone $now)->modify('-1 day');
$tomorrow = (clone $now)->modify('+1 day');
// Chercher le programme en cours
$ongoingProgramFound = false;
foreach ($chs as $ch) {
$chId = $ch->getAttribute("id");
$progs = $xp->query("/tv/programme[@channel='$chId']");
foreach ($progs as $p) {
$start = new DateTime($p->getAttribute("start"), new DateTimeZone("Europe/Paris"));
$stop = new DateTime($p->getAttribute("stop"), new DateTimeZone("Europe/Paris"));
if (strtotime($p->getAttribute("start")) <= strtotime($now->format(DateTime::ATOM)) && strtotime($now->format(DateTime::ATOM)) < strtotime($p->getAttribute("stop"))) {
$startWindow = $start;
$endWindow = (clone $start)->modify('+7 day');
$ongoingProgramFound = true;
break 2;
}}}
if (!$ongoingProgramFound) {
$startWindow = (clone $now)->modify('-85 minutes');
$endWindow = (new DateTime())->setTime(6, 0, 0)->modify('+7 day');
}
foreach ($chs as $ch) {
$chId = $ch->getAttribute("id");
$dispName = @$ch->getElementsByTagName("display-name")[0]->nodeValue ?: 'Nom de chaîne non disponible';
echo "<div class='tvprog'>";
echo "<div class='f20px tvchaine'>📺&nbsp;$dispName</div>";
$progs = $xp->query("/tv/programme[@channel='$chId']");
$progCount = 0;
$progTitles = [];
foreach ($progs as $p) {
$start = new DateTime($p->getAttribute("start"), new DateTimeZone("Europe/Paris"));
$stop = new DateTime($p->getAttribute("stop"), new DateTimeZone("Europe/Paris"));
$duration = $stop->getTimestamp() - $start->getTimestamp();
$title = @$p->getElementsByTagName("title")[0]->nodeValue ?: 'Titre non disponible';
$isToday = $start->format('Y-m-d') === $now->format('Y-m-d');
$isYesterday = $start->format('Y-m-d') === $yesterday->format('Y-m-d');
$isTomorrow = $start->format('Y-m-d') === $tomorrow->format('Y-m-d');
if ($start >= $startWindow && $start <= $endWindow) {
$heure = $start->format("H:i");
if ($isToday) {
$displayTitle = $title;
} elseif ($isYesterday) {
$displayTitle = $title . ' (hier)';
} elseif ($isTomorrow) {
$displayTitle = $title . ' (demain)';
} else {
$displayTitle = $title . ' (' . $start->format('d/m') . ')';
}
$displayDuration = "Durée: " . gmdate("H:i", $duration);
$progTitles[$title] = true;
$shown[] = $heure . ' - ' . $displayTitle . ' ' . $displayDuration;
if ($progCount < 15) {
$progCount++;
echo "<div class='tvprogitem'><span class='tvprogheure'>$heure&nbsp;</span><span class='tvprogtitle'>" . mb_strimwidth($displayTitle, 0, 56, "...") . "</span><span class='tvprogduree'>$displayDuration</span></div>";
}
}
}
echo "</div>";
}
} catch (Exception $e) {} ?>

</div>
</div>

<div class="messages retour_ligne_on hauteur_auto"><?php @include 'Structure/php/modules/messages.php'; ?></div>
<div class="menu bot"><?php @include "Structure/php/modules/menu_tv.php"; ?></div>
<div class="menu bot"><?php @include 'Structure/php/modules/menu.php'; ?></div>
</body>

</html>
<?php
 $p = ob_get_clean();
 if (substr_count($p, '<!DOCTYPE html>') === 1 && substr_count($p, '</html>') === 1 && strlen($p) >= 1024) {
echo $p;
@file_put_contents($nom_page . '.html', $p);
@chmod($nom_page . '.html', 0775);
@copy($nom_page . '.html', $nom_page . date("j") . '.html');
@chmod($nom_page . date("j") . '.html', 0775);
 }
} else {
 @readfile($nom_page . '.html');
};?>
<?php @include 'Structure/php/modules/stats.php';