<?php function parse_complet($fichier, $logo, $lienSite, $w, $h, $titreSite)
{
  $id = uniqid() . $logo . uniqid();
  try {
    $xml = simplexml_load_file('News/copy/' . $fichier . '.xml');
    $programmes = $xml->xpath('//programme');
    usort($programmes, function ($a, $b) {
      return strtotime(substr($a['start'], 0, -6)) - strtotime(substr($b['start'], 0, -6));
    });
  } catch (Exception $e) {
    return;
  }
  echo '<div class="boite"><div class="progtvcomplet"><div class="progtv"><div class="menutv"><a href="tv.php" class="inactif"><div class="titremenutv">⏰ Programme</div></a> | <a href="tv_00.php" class="inactif"><div class="titremenutv">🕛 Nuit</div></a> | <a href="tv_06.php" class="inactif"><div class="titremenutv">🕕 Matin</div></a> | <a href="tv_12.php" class="inactif"><div class="titremenutv">🕛 Après-midi</div></a> | <a href="tv_18.php" class="inactif"><div class="titremenutv">🕕 Soir</div></a> | <a href="tv_demain.php" class="actif"><div class="titremenutv">📅 Demain</div></a> | <a href="tv_films.php" class="inactif"><div class="titremenutv">🍿 Films</div></a> | <a href="tv_series.php" class="inactif"><div class="titremenutv">🎬 Séries</div></a> | <a href="tv_docs.php" class="inactif"><div class="titremenutv">🎥 Documentaires</div></a> | <a href="tv_jeunesse.php" class="inactif"><div class="titremenutv">🌈 Jeunesse</div></a></div>';
  foreach ($xml->channel as $channel) {
    echo '<div class="chainetv"><span><span class="nomchainetv">📺 ' . $channel->{'display-name'} . '</span></span>';
    $progCount = 0;
    $primeTimeCount = 0;
    $primeTimeStart = strtotime('20:49:59 +1 day');
    $primeTimeEnd = strtotime('23:59:59 +1 day');
    foreach ($programmes as $programme) {
      if ((string) $programme['channel'] != (string) $channel['id'])
        continue;
      $start = strtotime(substr($programme['start'], 0, -6));
      $stop = strtotime(substr($programme['stop'], 0, -6));
      $duration = ($stop - $start) / 60;
      $startchoose = strtotime('00:00:00 +1 day');
      $endchoose = strtotime('23:59:59 +1 day');
      if (date('Y-m-d', $start) == date('Y-m-d', strtotime("+1 day")) && $start >= $startchoose && $start <= $endchoose && $progCount < 144 && $duration > 10) {
        $fullTitle = htmlspecialchars(str_replace("\\", "", str_replace("\"", "’", str_replace("'", "’", @strip_tags(@mb_convert_encoding($programme->title, 'UTF-8', 'auto'))))));
        if ($fullTitle === false || mb_check_encoding($fullTitle, 'UTF-8') === false) {
          continue;
        }
        $title = htmlspecialchars(str_replace("\\", "", str_replace("\"", "’", str_replace("'", "’", @strip_tags(strlen($fullTitle) > 400 ? substr($fullTitle, 0, strrpos(substr($fullTitle, 0, 400), ' ')) . "..." : $fullTitle)))));
        $formattedDuration = formatDuration2($duration);
        $descfull = htmlspecialchars(str_replace("\\", "", str_replace("\"", "’", str_replace("'", "’", @strip_tags(@mb_convert_encoding($programme->desc, 'UTF-8', 'auto'))))));
        if ($descfull === false || mb_check_encoding($descfull, 'UTF-8') === false) {
          continue;
        }
        $descfull = strlen($descfull) > 900 ? substr($descfull, 0, strrpos(substr($descfull, 0, 900), ' ')) . "..." : $descfull;
        $descfull = empty($descfull) ? "Sans descriptif" : $descfull;
        $emoji = '⭕';
        switch ($programme->category) {
          case 'Film':
            $emoji = '🍿';
            break;
          case 'Série':
            $emoji = '🎬';
            break;
          case 'Documentaire':
            $emoji = '🎥';
            break;
          case 'Magazine':
            $emoji = '📚';
            break;
          case 'Info-Météo':
            $emoji = '📰';
            break;
          case 'Divertissement':
            $emoji = '🎭';
            break;
          case 'Sport':
            $emoji = '🏆';
            break;
          case 'Musique':
            $emoji = '🎵';
            break;
          case 'Jeunesse':
            $emoji = '🌈';
            break;
          case 'Evénement':
            $emoji = '🎭';
            break;
          case 'Culture':
            $emoji = '🧐';
            break;
          case 'Action':
            $emoji = '🍿';
            break;
        }
        if ($start >= $primeTimeStart && $start <= $primeTimeEnd && $duration > 26) {
          echo '<div class="prog" title="' . $descfull . '"><span class="primetime">' . date("H:i", $start) . ' ' . $emoji . ' <span class="progtvtitre">' . $title . '</span> - ' . $programme->category . ' - ' . $formattedDuration . ' : ' . $descfull . '</span></div>';
          $primeTimeCount++;
          if ($primeTimeCount >= 5) {
            break;
          }
        } else {
          echo '<div class="prog" title="' . $descfull . '"><span>' . date("H:i", $start) . ' ' . $emoji . ' ' . $title . ' - ' . $programme->category . ' - ' . $formattedDuration . '</span></div>';
        }
        $progCount++;
      }
    }
    echo '</div>';
  }
  echo '</div></div></div>';
}
function formatDuration2($duration)
{
  $hours = (int) ($duration / 60);
  $minutes = $duration % 60;
  if ($hours >= 1) {
    return sprintf("%dh%02d", $hours, $minutes);
  } else {
    return sprintf("%d mn", $minutes);
  }
}
