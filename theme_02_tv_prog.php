<?php
$titre_page = 'Tv';
$nom_page = 'theme_02_tv_prog';
$nom_page_theme_alternatif = 'tv.php';
$page_active = 'tv';
$page_tv_active= 'programme';
$titre_page_active = '&#129302;&nbsp;Tv';
$cache =245;
$theme = '01';
$lien_menu_theme = 'menu_theme_02';

if (!file_exists($nom_page . '.html') || filemtime($nom_page . '.html') < (time() - $cache) || !file_exists($nom_page . date("j") . '.html')) {
    ob_start(); ?>

<!DOCTYPE html>
<html lang="fr">

 <head>
  <?php @include "Structure/php/modules/header.php"; ?>
  <style type="text/css">
  <?php @readfile("Structure/css/fonts.css");
  @readfile("Structure/css/base.css");
  @readfile("Structure/css/". $theme . ".css");

  ?>.programmes {
   margin-bottom: round(up, calc(16 / 1920 * 100vw), 8px);
  }

  .programme-item {
   display: flex;
   justify-content: stretch;
   justify-items: start;
   align-items: center;
   padding: round(up, calc(4 / 1920 * 100vw), 4px) round(up, calc(12 / 1920 * 100vw), 6px);
   align-items: center;
  }

  .programme-time {
   display: inline;
  }

  .programme-title {
   flex: 1;
  }

  .current-programme {
   background-color: rgba(32, 64, 192, 0.9);
  }
  </style>
 </head>

 <body id="body" lang="fr">
  <div class="flex-page">
   <div class="menu"><?php @include 'Structure/php/modules/'.$lien_menu_theme.'.php'; ?></div>
   <div class="menu"><?php @include "Structure/php/modules/menu_tv_theme_02.php"; ?></div>
   <div class="mid">
    <?php
            try {
                // Charge le fichier XML et initialise DOMXPath
                $xmlFile = "Structure/cache/tv/xmltv_tnt.xml";
                $xml = new DOMDocument();
                if (!$xml->load($xmlFile)) {
                    throw new Exception(" ");
                }

                $xp = new DOMXPath($xml);
                $chs = $xp->query("/tv/channel");

                $now = new DateTime("now", new DateTimeZone("Europe/Paris"));
                $shown = [];
                $prevTitle = '';

                // Fonction pour trouver le programme "current-programme"
                function findCurrentProgramme($progs, $now) {
                    foreach ($progs as $p) {
                        $start = new DateTime($p->getAttribute("start"), new DateTimeZone("Europe/Paris"));
                        $stop = new DateTime($p->getAttribute("stop"), new DateTimeZone("Europe/Paris"));
                        if ($now >= $start && $now <= $stop) {
                            return $start;
                        }
                    }
                    return null;
                }

                // Trouver le programme "current-programme"
                $currentProgrammeStart = findCurrentProgramme($xp->query("/tv/programme"), $now);

                // Calculer la tranche horaire à afficher
                $startWindow = (clone $currentProgrammeStart)->modify('-215 minutes');
                $endWindow = (new DateTime())->setTime(6, 0, 0)->modify('+7 day');

                foreach ($chs as $ch) {
                    $chId = $ch->getAttribute("id");
                    $dispName = $ch->getElementsByTagName("display-name")[0]->nodeValue;
                    echo "<div style='background-color: var(--c8);color: var(--c2);font-synthesis-small-caps:auto;font-synthesis-weight:auto;font-synthesis:style;font-variant-emoji:emoji;font-variant:discretionary-ligatures tabular-nums;outline:round(calc(4 / 1920 * 100vw),1px) solid var(--c7);outline-offset:round(calc(-3 / 1920 * 100vw),1px);border-radius:round(calc(12 / 1920 * 100vw),1px);padding:round(up,calc(8 / 1920 * 100vw),1px); margin-bottom:round(up,calc(6 / 1920 * 100vw),1px);'>";
                    echo "<div class='f20px' style='border-bottom:round(up,calc(1 / 1920 * 100vw),1px) solid var(--c7);margin:round(up,calc(6 / 1920 * 100vw),1px) round(calc(1 / 1920 * 100vw),1px) 0 auto;padding:round(up,calc(6 / 1920 * 100vw),1px) round(calc(6 / 1920 * 100vw),1px) round(up,calc(12 / 1920 * 100vw),1px) round(calc(6 / 1920 * 100vw),1px);color: var(--c10);font-variation-settings: \"wght\" 600;'>📺&nbsp;$dispName</div>";

                    $progs = $xp->query("/tv/programme[@channel='$chId']");
                    $progCount = 0;
                    $progTitles = [];

                    foreach ($progs as $p) {
                        $start = new DateTime($p->getAttribute("start"), new DateTimeZone("Europe/Paris"));
                        $stop = new DateTime($p->getAttribute("stop"), new DateTimeZone("Europe/Paris"));
                        $duration = $stop->getTimestamp() - $start->getTimestamp();
                        $title = $p->getElementsByTagName("title")[0]->nodeValue;

                        if ($start >= $startWindow && $start <= $endWindow) {
                            $time = $start->format("H:i");
                            $class = '';

                            // Afficher toujours les programmes "current-programme"
                            if ($now >= $start && $now <= $stop) {
                                $class = 'current-programme';
                            }

                            // Condition modifiée pour toujours afficher les programmes "current-programme"
                            if (!isset($progTitles[$title]) && ($class == 'current-programme' || $duration >= 300)) {
                                $progTitles[$title] = true;
                                $shown[] = $time . ' - ' . $title;
                                $prevTitle = $title;

                                if ($class == 'current-programme' || $progCount < 12) {
                                    $progCount++;
                                    echo "<div class='programme-item $class'><div class='programme-time'>$time&nbsp;</div><div class='programme-title'>$title</div></div>";
                                }
                            } elseif ($class == 'current-programme') {
                                echo "<div class='programme-item $class'><div class='programme-time'>$time&nbsp;</div><div class='programme-title'>$title</div></div>";
                            }
                        }
                    }
                    echo "</div>";
                }
            } catch (Exception $e) {
                // Silencieux : ne pas afficher d'erreur à l'utilisateur
            }
            ?>
   </div>
  </div>

  <div class="messages retour_ligne_on hauteur_auto"><?php @include 'Structure/php/modules/messages.php'; ?></div>
  <div class="menu bot"><?php @include "Structure/php/modules/menu_tv_theme_02.php"; ?></div>
  <div class="menu bot"><?php @include 'Structure/php/modules/'.$lien_menu_theme.'.php'; ?></div>
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