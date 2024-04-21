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
    echo '<div class="tv-chaine"><span class="tv-chaine-nom"><span class="tv-chaine-logo"></span>' . $channel->{'display-name'} . '</span></div><div class="tv-chaine-listeprogs">';
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
        $descfull = htmlspecialchars(str_replace("\\", "", str_replace("\"", "’", str_replace("'", "’", @strip_tags(@mb_convert_encoding($programme->desc, 'UTF-8', 'auto'))))));
        $imgsrc = $programme->icon['src'];
        if ($descfull === false || mb_check_encoding($descfull, 'UTF-8') === false) {
          continue;
        }
        $descfull = strlen($descfull) > $longdesc ? substr($descfull, 0, strrpos(substr($descfull, 0, $longdesc), ' ')) . "..." : $descfull;
        $descfull = empty($descfull) ? "Sans descriptif" : $descfull;
        $emoji = '💤';
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
          case 'Sport':
            $emoji = '⚽';
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
        }

        $progpicture = ' ';
        if ($afficheimg) {
          $progpicture = '<img src="structure/img/tv/' . $progimg . '.webp" width="128" height="128" class="prog-img">';
        }

        $progpicture_src = ' ';
        if ($afficheimg_src) {
          $progpicture_src = '<img src="' . $imgsrc . '" width="128" height="72" class="prog-img-ratio-16-9">';
        }

        $progemoji = ' ';
        if ($afficheemoji) {
          $progemoji = '<div class="tv-chaine-prog-contenu-tv-chaine-prog-contenu-categorie">' . $emoji . '' . $programme->category . '</div>';
        }

        $progdesc = ' ';
        $progdesctitle = 'title="' . $descfull . '"';
        if ($affichedesc) {
          $progdesc = '<div class="tv-chaine-prog-contenu-tv-chaine-prog-contenu-desc">' . $descfull . '</div>';
          $progdesctitle = ' ';
        }

        echo '<div class="tv-chaine-prog" ' . $progdesctitle . '>' . $progpicture . $progpicture_src . '<div class="tv-chaine-prog-contenu"><div class="tv-chaine-prog-contenu-heure">' . date("H:i", $start) . '</div><div class="tv-chaine-prog-contenu-titre">' . $title . '' . $progemoji . '</div>' . $progdesc . '</div></div>';

        $progCount++;
      }
    }
    echo '</div>';
  }
  echo '</div>';
}
