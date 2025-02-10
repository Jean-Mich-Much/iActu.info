<?php function lit_tv($filename, $choicestart, $choiceend, $choiceduration, $howmanyprogs, $longdesc, $longtitle, $showimg, $showcat, $showdesc, $showimg_src, $starttomorow, $endtomorow, $demain, $prefix)
{
  try {
    $xml = simplexml_load_file('Structure/cache/source/tv.xml');
    $progs = $xml->xpath('//programme');
    usort($progs, function ($a, $b) {
      return strtotime(substr($a['start'], 0, -6)) - strtotime(substr($b['start'], 0, -6));
    });
  } catch (Exception $e) {
    return;
  }
  foreach ($xml->channel as $channel) {
    $progCount = 0;
    $namechannelimg = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', $channel->{'display-name'}));
    $pathchannelimg = "Structure/img/tv/channels/" . $prefix.$namechannelimg . ".webp";
    echo '<div class="tv-channel c' . $namechannelimg . '"><div class="tv-logo"><img src="' . $pathchannelimg . '" width="80" height="240" alt="' . $channel->{'display-name'} . '" title="' . $channel->{'display-name'} . '" id="' . $namechannelimg . date('His') . '"></div>';
    foreach ($progs as $programme) {
      if ((string) $programme['channel'] != (string) $channel['id'])
        continue;
      $start = strtotime(substr($programme['start'], 0, -6));
      $stop = strtotime(substr($programme['stop'], 0, -6));
      $duree = ($stop - $start) / 60;
      $debut = strtotime($choicestart);
      $fin = strtotime($choiceend);
      $debutplus1 = strtotime('+1 days', strtotime($starttomorow));
      $finplus1 = strtotime('+1 days', strtotime($endtomorow));

      $datedemain = date('Y-m-d');
      $debutprime20 = strtotime('19:59:59');
      $debutprime19 = strtotime('18:59:59');
      $debutprime18 = strtotime('17:59:59');
      $debutprime17 = strtotime('16:59:59');
      $debutprime16 = strtotime('15:59:59');
      $debutprime15 = strtotime('14:59:59');
      $debutprime14 = strtotime('13:59:59');
      $debutprime13 = strtotime('12:59:59');
      $debutprime12 = strtotime('11:59:59');
      $debutprime11 = strtotime('10:59:59');
      $debutprime10 = strtotime('09:59:59');
      $debutprime09 = strtotime('08:59:59');
      $debutprime08 = strtotime('07:59:59');
      $finprime = strtotime('21:11:59');
      if ($demain) {
        $datedemain = date('Y-m-d', strtotime('+1 days'));
        $debutprime20 = strtotime('+1 days', strtotime('19:59:59'));
        $finprime = strtotime('+1 days', strtotime('21:11:59'));
        $debutprime19 = strtotime('+1 days', strtotime('18:59:59'));
        $debutprime18 = strtotime('+1 days', strtotime('17:59:59'));
        $debutprime17 = strtotime('+1 days', strtotime('16:59:59'));
        $debutprime16 = strtotime('+1 days', strtotime('15:59:59'));
        $debutprime15 = strtotime('+1 days', strtotime('14:59:59'));
        $debutprime14 = strtotime('+1 days', strtotime('13:59:59'));
        $debutprime13 = strtotime('+1 days', strtotime('12:59:59'));
        $debutprime12 = strtotime('+1 days', strtotime('11:59:59'));
        $debutprime11 = strtotime('+1 days', strtotime('10:59:59'));
        $debutprime10 = strtotime('+1 days', strtotime('09:59:59'));
        $debutprime09 = strtotime('+1 days', strtotime('08:59:59'));
        $debutprime08 = strtotime('+1 days', strtotime('07:59:59'));
      }

      if (
        (((date('Y-m-d', $start) == $datedemain && $start >= $debutprime20 && $start <= $finprime)) && $progCount < $howmanyprogs && $duree > 72) or (((date('Y-m-d', $start) == date('Y-m-d') && $start >= $debut && $start <= $fin) or (date('Y-m-d', $start) == date('Y-m-d', strtotime('+1 days')) && $start >= $debutplus1 && $start <= $finplus1)) && $progCount < $howmanyprogs && $duree > $choiceduration)
        or (((date('Y-m-d', $start) == $datedemain && $start >= $debutprime19 && $start <= $finprime)) && $progCount < $howmanyprogs && $duree > 132)
        or (((date('Y-m-d', $start) == $datedemain && $start >= $debutprime18 && $start <= $finprime)) && $progCount < $howmanyprogs && $duree > 192)
        or (((date('Y-m-d', $start) == $datedemain && $start >= $debutprime17 && $start <= $finprime)) && $progCount < $howmanyprogs && $duree > 252)
        or (((date('Y-m-d', $start) == $datedemain && $start >= $debutprime16 && $start <= $finprime)) && $progCount < $howmanyprogs && $duree > 312)
        or (((date('Y-m-d', $start) == $datedemain && $start >= $debutprime15 && $start <= $finprime)) && $progCount < $howmanyprogs && $duree > 372)
        or (((date('Y-m-d', $start) == $datedemain && $start >= $debutprime14 && $start <= $finprime)) && $progCount < $howmanyprogs && $duree > 432)
        or (((date('Y-m-d', $start) == $datedemain && $start >= $debutprime13 && $start <= $finprime)) && $progCount < $howmanyprogs && $duree > 492)
        or (((date('Y-m-d', $start) == $datedemain && $start >= $debutprime12 && $start <= $finprime)) && $progCount < $howmanyprogs && $duree > 552)
        or (((date('Y-m-d', $start) == $datedemain && $start >= $debutprime11 && $start <= $finprime)) && $progCount < $howmanyprogs && $duree > 612)
        or (((date('Y-m-d', $start) == $datedemain && $start >= $debutprime10 && $start <= $finprime)) && $progCount < $howmanyprogs && $duree > 672)
        or (((date('Y-m-d', $start) == $datedemain && $start >= $debutprime09 && $start <= $finprime)) && $progCount < $howmanyprogs && $duree > 732)
        or (((date('Y-m-d', $start) == $datedemain && $start >= $debutprime08 && $start <= $finprime)) && $progCount < $howmanyprogs && $duree > 792)
      ) {
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
        $imgcat = 'autres';
        $csscat = '';
        switch ($programme->category) {
          case 'Film':
            $emoji = '🎞️';
            $imgcat = 'film';
            $csscat = ' class="tv-cat-film"';
            break;
          case 'Série':
            $emoji = '🎬';
            $imgcat = 'serie';
            $csscat = ' class="tv-cat-serie"';
            break;
          case 'Documentaire':
            $emoji = '🚀';
            $imgcat = 'documentaire';
            break;
          case 'Magazine':
            $emoji = '🗞️';
            $imgcat = 'divertissement';
            break;
          case 'Info-Météo':
            $emoji = '📰';
            $imgcat = 'documentaire';
            break;
          case 'Divertissement':
            $emoji = '🎭';
            $imgcat = 'divertissement';
            break;
          case 'Magazine du cinéma':
            $emoji = '🎭';
            $imgcat = 'divertissement';
            break;
          case 'Sport':
            $emoji = '⚽';
            $imgcat = 'sport';
            break;
          case 'Football':
            $emoji = '⚽';
            $imgcat = 'sport';
            break;
          case 'Rugby':
            $emoji = '🏉';
            $imgcat = 'sport';
            break;
          case 'Tennis':
            $emoji = '🎾';
            $imgcat = 'sport';
            break;
          case 'Golf':
            $emoji = '⛳';
            $imgcat = 'sport';
            break;
          case 'Rallye':
            $emoji = '🏎️';
            $imgcat = 'sport';
            break;
          case 'Musique':
            $emoji = '🎵';
            $imgcat = 'divertissement';
            break;
          case 'Jeunesse':
            $emoji = '🐻';
            $imgcat = 'jeunesse';
            break;
          case 'Evénement':
            $emoji = '🎭';
            $imgcat = 'divertissement';
            break;
          case 'Culture':
            $emoji = '🧐️';
            $imgcat = 'divertissement';
            break;
          case 'Action':
            $emoji = '🎞️';
            $imgcat = 'film';
            $csscat = ' class="tv-cat-film"';
            break;
          case 'Comédie dramatique':
            $emoji = '🎞️';
            $imgcat = 'film';
            $csscat = ' class="tv-cat-film"';
            break;
          case 'Comédie':
            $emoji = '🎞️';
            $imgcat = 'film';
            $csscat = ' class="tv-cat-film"';
            break;
          case 'Drame':
            $emoji = '🎞️';
            $imgcat = 'film';
            $csscat = ' class="tv-cat-film"';
            break;
          case 'Film policier':
            $emoji = '🎞️';
            $imgcat = 'film';
            $csscat = ' class="tv-cat-film"';
            break;
          case 'Biographie':
            $emoji = '🎞️';
            $imgcat = 'film';
            $csscat = ' class="tv-cat-film"';
            break;
        }

        $picturecat = '';
        if ($showimg) {
          $picturecat = "Structure/img/tv/' . $imgcat . '.webp";
        }

        $picture_src = '';
        $thumbnail = '';
        if ($showimg_src) {
          $imgsrc = filter_var($imgsrc, FILTER_SANITIZE_URL);
          if (filter_var($imgsrc, FILTER_VALIDATE_URL)) {
            $extension = pathinfo($imgsrc, PATHINFO_EXTENSION);
            $filename = preg_replace('/[^A-Za-z0-9\-]/', '', $imgsrc);
            $filename = strtr(strtolower($filename), 'abcdefghijklmnopqrstuvwxyz', '12345678912345678912345678');
            $filename = hash('md5', $filename);
            $filename = str_replace('0', '', $filename);
            $filename = $filename . "." . $extension;
            $destinationPath = "Structure/cache/tv/" . $filename;
            if (!file_exists($destinationPath)) {
              $headers = @get_headers($imgsrc);
              if ($headers && strpos($headers[0], '200')) {
                @copy($imgsrc, $destinationPath);
                usleep(1000);
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $destinationPath);
                finfo_close($finfo);
                if (!preg_match('/^image\//', $mime)) {
                  $destinationPath = "Structure/img/tv/" . $imgcat . ".webp";
                }
              } else {
                $destinationPath = "Structure/img/tv/" . $imgcat . ".webp";
              }
            }
            if (file_exists($destinationPath)) {
              $picture_src = $destinationPath;
            } else {
              $destinationPath = "Structure/img/tv/" . $imgcat . ".webp";
              $picture_src = $destinationPath;
            }
          } else {
            $destinationPath = "Structure/img/tv/" . $imgcat . ".webp";
            $picture_src = $destinationPath;
          }
          $thumbnail = '<div class="tv-cat-img"><img src="' . $picturecat . $picture_src . '" width="160" height="90" alt="' . $channel->{'display-name'} . '" title="' . $title . '" id="' . $picturecat . $picture_src . date(" Hi", $start) . '"></div>';
        }

        $category = '';
        if ($showcat) {
          $category = '<div' . $csscat . '>' . $emoji . '&nbsp;'.$programme->category . '</div>';
        }

        $descshort = '';
        $showdescshort = '';
        if ($showdesc) {
          $descshort = str_replace('Aucune description', '', $desc);
          $showdescshort = '<div class="tv-desc" title="📃' . $desclong . '">' . str_replace('Info-Météo', 'Infos', $category) . '<div class="tv-duree">⏱️&nbsp;' . $dureeFormatee . '</div><div class="tv-descshort">' . $descshort . '</div></div> ';
        }

        echo '
       <div class="tv-master">
        <div class="tv-prog">
        <div class="tv-start-title">
         <div class="tv-start">&#9200;&nbsp;' . date("H:i", $start) . '</div>
         <div class="tv-title" title="&nbsp;' . $title . '&nbsp;"> &#128250;&nbsp;' . $title . '</div></div>' . $thumbnail . $showdescshort . ' 
         </div>
      </div>';

        $progCount++;
      }
    }
    echo '</div>';
  }

  $delfiles = glob('Structure/cache/tv/*');
  $delnow = time();

  foreach ($delfiles as $delfile) {
    if (is_file($delfile)) {
      if ($delnow - filemtime($delfile) >= 7776000) {
        unlink($delfile);
      }
    }
  }

}

function formatDuree($minutes)
{
  $heures = floor($minutes / 60);
  $minutes = $minutes % 60;
  return $heures > 0 ? $heures . "h" . str_pad($minutes, 2, "0", STR_PAD_LEFT) . "mn" : $minutes . " mn";
}
