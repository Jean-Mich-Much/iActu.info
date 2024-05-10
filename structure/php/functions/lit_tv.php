<?php function lit_tv($filename, $choicestart, $choiceend, $choiceduration, $howmanyprogs, $longdesc, $longtitle, $showimg, $showcat, $showdesc, $showimg_src, $starttomorow, $endtomorow)
{
  try {
    $xml = simplexml_load_file('News/copy/' . $filename . '.xml');
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
    $pathchannelimg = "structure/img/tv/channels/" . $namechannelimg . "90tv.webp";
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
      if (((date('Y-m-d', $start) == date('Y-m-d') && $start >= $debut && $start <= $fin) or (date('Y-m-d', $start) == date('Y-m-d', strtotime('+1 days')) && $start >= $debutplus1 && $start <= $finplus1)) && $progCount < $howmanyprogs && $duree > $choiceduration) {
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
          $picturecat = "structure/img/tv/' . $imgcat . '.webp";
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
                  $destinationPath = "structure/img/tv/" . $imgcat . ".webp";
                }
              } else {
                $destinationPath = "structure/img/tv/" . $imgcat . ".webp";
              }
            }
            if (file_exists($destinationPath)) {
              $picture_src = $destinationPath;
            } else {
              $destinationPath = "structure/img/tv/" . $imgcat . ".webp";
              $picture_src = $destinationPath;
            }
          } else {
            $destinationPath = "structure/img/tv/" . $imgcat . ".webp";
            $picture_src = $destinationPath;
          }
          $thumbnail = '<div class="tv-cat-img"><img src="' . $picturecat . $picture_src . '" width="160" height="90" alt="' . $channel->{'display-name'} . '" title="' . $title . '" id="' . $picturecat . $picture_src . date(" Hi", $start) . '"></div>';
        }

        $category = '';
        if ($showcat) {
          $category = '<div' . $csscat . '>' . $emoji . $programme->category . '</div>';
        }

        $descshort = '';
        $showdescshort = '';
        if ($showdesc) {
          $descshort = str_replace('Aucune description', '', $desc);
          $showdescshort = '<div class="tv-desc" title="📃' . $desclong . '">' . str_replace('Info-Météo', 'Infos', $category) . '<div class="tv-duree">⏱️' . $dureeFormatee . '</div><div class="tv-descshort">' . $descshort . '</div></div> ';
        }

        echo '
       <div class="tv-master">
        <div class="tv-prog">
        <div class="tv-start-title">
         <div class="tv-start">' . date("H:i", $start) . '</div>
         <div class="tv-title" title="&nbsp;' . $title . '&nbsp;">' . $title . '</div></div>' . $thumbnail . $showdescshort . ' 
         </div>
      </div>';

        $progCount++;
      }
    }
    echo '</div>';
  }

  $delfiles = glob('News/tmp/*');
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
