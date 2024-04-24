<?php function lit_tv($fichier, $css_class, $choixdebut, $choixfin, $choixduree, $nbreprogs, $longdesc, $longtitle, $afficheimg, $afficheemoji, $affichedesc, $afficheimg_src)
{
  try {
    $xml = simplexml_load_file('News/copy/' . $fichier . '.xml');
    $progs = $xml->xpath('//programme');
    usort($progs, function ($a, $b) {
      return strtotime(substr($a['start'], 0, -6)) - strtotime(substr($b['start'], 0, -6));
    });
  } catch (Exception $e) {
    return;
  }
  echo '<div class="' . $css_class . '">';
  foreach ($xml->channel as $channel) {
    echo '<div class="tv-chaines"><div class="tv-chaine"><span class="tv-chaine-nom"><span class="tv-chaine-logo"></span>' . $channel->{'display-name'} . '</span></div><div class="tv-chaine-listeprogs">';
    $progCount = 0;
    foreach ($progs as $programme) {
      if ((string) $programme['channel'] != (string) $channel['id'])
        continue;
      $start = strtotime(substr($programme['start'], 0, -6));
      $stop = strtotime(substr($programme['stop'], 0, -6));
      $duree = ($stop - $start) / 60;
      $debut = strtotime($choixdebut);
      $fin = strtotime($choixfin);
      if (date('Y-m-d', $start) == date('Y-m-d') && $start >= $debut && $start <= $fin && $progCount < $nbreprogs && $duree > $choixduree) {
        $fullTitle = htmlspecialchars(str_replace("\\", "", str_replace("\"", "’", str_replace("'", "’", @strip_tags(@mb_convert_encoding($programme->title, 'UTF-8', 'auto'))))));
        if ($fullTitle === false || mb_check_encoding($fullTitle, 'UTF-8') === false) {
          continue;
        }
        $title = strlen($fullTitle) > $longtitle ? substr($fullTitle, 0, strrpos(substr($fullTitle, 0, $longtitle), ' ')) . "..." : $fullTitle;

        $desc = htmlspecialchars(str_replace("\\", "", str_replace("\"", "’", str_replace("'", "’", @strip_tags(@mb_convert_encoding($programme->desc, 'UTF-8', 'auto'))))));
        $imgsrc = $programme->icon['src'];
        if ($desc === false || mb_check_encoding($desc, 'UTF-8') === false) {
          continue;
        }
        $desc = strlen($desc) > $longdesc ? substr($desc, 0, strrpos(substr($desc, 0, $longdesc), ' ')) . "..." : $desc;
        $desc = empty($desc) ? "Sans descriptif" : $desc;

        $desclong = htmlspecialchars(str_replace("\\", "", str_replace("\"", "’", str_replace("'", "’", @strip_tags(@mb_convert_encoding($programme->desc, 'UTF-8', 'auto'))))));
        $imgsrc = $programme->icon['src'];
        if ($desclong === false || mb_check_encoding($desclong, 'UTF-8') === false) {
          continue;
        }

        $dureeFormatee = formatDuree($duree);

        $desclongcalc = $longdesc * 4;
        $desclong = strlen($desclong) > $longdesc ? substr($desclong, 0, strrpos(substr($desclong, 0, $desclongcalc), ' ')) . "..." : $desclong;
        $desclong = empty($desclong) ? "Sans descriptif" : $desclong;

        $emoji = '💤';
        $progimg = 'autres';
        switch ($programme->category) {
          case 'Film':
            $emoji = '🎞️';
            $progimg = 'film';
            break;
          case 'Série':
            $emoji = '🎬';
            $progimg = 'serie';
            break;
          case 'Documentaire':
            $emoji = '🚀';
            $progimg = 'documentaire';
            break;
          case 'Magazine':
            $emoji = '🗞️';
            $progimg = 'divertissement';
            break;
          case 'Info-Météo':
            $emoji = '📰';
            $progimg = 'documentaire';
            break;
          case 'Divertissement':
            $emoji = '🎭';
            $progimg = 'divertissement';
            break;
          case 'Magazine du cinéma':
            $emoji = '🎭';
            $progimg = 'divertissement';
            break;
          case 'Sport':
            $emoji = '⚽';
            $progimg = 'sport';
            break;
          case 'Football':
            $emoji = '⚽';
            $progimg = 'sport';
            break;
          case 'Rugby':
            $emoji = '🏉';
            $progimg = 'sport';
            break;
          case 'Tennis':
            $emoji = '🎾';
            $progimg = 'sport';
            break;
          case 'Golf':
            $emoji = '⛳';
            $progimg = 'sport';
            break;
          case 'Rallye':
            $emoji = '🏎️';
            $progimg = 'sport';
            break;
          case 'Musique':
            $emoji = '🎵';
            $progimg = 'divertissement';
            break;
          case 'Jeunesse':
            $emoji = '🐻';
            $progimg = 'jeunesse';
            break;
          case 'Evénement':
            $emoji = '🎭';
            $progimg = 'divertissement';
            break;
          case 'Culture':
            $emoji = '🧐️';
            $progimg = 'divertissement';
            break;
          case 'Action':
            $emoji = '🎞️';
            $progimg = 'film';
            break;
          case 'Comédie dramatique':
            $emoji = '🎞️';
            $progimg = 'film';
            break;
          case 'Comédie':
            $emoji = '🎞️';
            $progimg = 'film';
            break;
          case 'Drame':
            $emoji = '🎞️';
            $progimg = 'film';
            break;
          case 'Film policier':
            $emoji = '🎞️';
            $progimg = 'film';
            break;
          case 'Biographie':
            $emoji = '🎞️';
            $progimg = 'film';
            break;
        }

        $progpicture = ' ';
        if ($afficheimg) {
          $progpicture = '<img src="structure/img/tv/' . $progimg . '.webp" width="128" height="128" class="prog-img">';
        }

        $progpicture_src = '';
        if ($afficheimg_src) {
          $imgsrc = filter_var($imgsrc, FILTER_SANITIZE_URL);
          if (filter_var($imgsrc, FILTER_VALIDATE_URL)) {
            $extension = pathinfo($imgsrc, PATHINFO_EXTENSION);
            $filename = preg_replace('/[^A-Za-z0-9\-]/', '', $imgsrc);
            $filename = strtr(strtolower($filename), 'abcdefghijklmnopqrstuvwxyz', '12345678912345678912345678');
            $filename = hash('md5', $filename);
            $filename = str_replace('0', '', $filename);
            $filename = $filename . "." . $extension;
            $destinationPath = "News/tmp/" . $filename;
            if (!file_exists($destinationPath)) {
              $headers = @get_headers($imgsrc);
              if ($headers && strpos($headers[0], '200')) {
                @copy($imgsrc, $destinationPath);
                usleep(1000);
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $destinationPath);
                finfo_close($finfo);
                if (!preg_match('/^image\//', $mime)) {
                  $destinationPath = "structure/img/tv/" . $progimg . ".webp";
                }
              } else {
                $destinationPath = "structure/img/tv/" . $progimg . ".webp";
              }
            }
            if (file_exists($destinationPath)) {
              $progpicture_src = '<img src="' . $destinationPath . '" width="128" height="72" class="prog-img-ratio-16-9">';
            } else {
              $destinationPath = "structure/img/tv/" . $progimg . ".webp";
              $progpicture_src = '<img src="' . $destinationPath . '" width="128" height="72" class="prog-img-ratio-16-9">';
            }
          } else {
            $destinationPath = "structure/img/tv/" . $progimg . ".webp";
            $progpicture_src = '<img src="' . $destinationPath . '" width="128" height="72" class="prog-img-ratio-16-9">';
          }
        }

        $progemoji = ' ';
        if ($afficheemoji) {
          $progemoji = '<div class="tv-chaine-prog-contenu-tv-chaine-prog-contenu-categorie">' . $emoji . '' . $programme->category . '</div>';
        }

        $progdesc = ' ';
        if ($affichedesc) {
          $progdesc = '<div class="tv-chaine-prog-contenu-tv-chaine-prog-contenu-desc">' . $desc . '</div>';
        }

        echo '<div class="tv-chaine-prog" title="⏳Durée :&nbsp;' . $dureeFormatee . '&nbsp;|&nbsp;📃Synopsis :&nbsp;' . $desclong . '">' . $progpicture . $progpicture_src . '<div class="tv-chaine-prog-contenu"><div class="tv-chaine-prog-contenu-heure">' . date("H:i", $start) . '</div><div class="tv-chaine-prog-contenu-titre">' . $title . '' . $progemoji . '</div>' . $progdesc . '</div></div>';

        $progCount++;
      }
    }
    echo '</div></div>';
  }
  echo '</div>';

  $delfiles = glob('News/tmp/*');
  $delnow = time();

  foreach ($delfiles as $delfile) {
    if (is_file($delfile)) {
      if ($delnow - filemtime($delfile) >= 2419200) {
        unlink($delfile);
      }
    }
  }

}

function formatDuree($minutes)
{
  $heures = floor($minutes / 60);
  $minutes = $minutes % 60;
  return $heures > 0 ? $heures . "h " . str_pad($minutes, 2, "0", STR_PAD_LEFT) . "mn" : $minutes . " mn";
}
