<?php
function parse_complet($fichier, $logo, $lienSite, $w, $h, $titreSite)
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
  echo '<div class="boite"><div class="enTete"><span class="lienSite"><a href="' . $lienSite . '" target="_blank" rel="noopener nofollow" title="Accueil du site"><picture><img src="structure/img/logos/100px/' . $logo . '.webp" srcset="structure/img/logos/50px/' . $logo . '.webp 1920w, structure/img/logos/100px/' . $logo . '.webp 3840w, structure/img/logos/200px/' . $logo . '.webp 7680w" width="' . $w . '" height="' . $h . '" alt="Accueil du site" title="Accueil du site" id="accueilsite' . $id . '"></picture><span>' . $titreSite . '</span></a></span></div><div class="progtvcomplet"><div class="progtv">';
  foreach ($xml->channel as $channel) {
    echo '<div class="chainetv"><span><span class="nomchainetv">🎥 ' . $channel->{'display-name'} . '</span></span>';
    foreach ($programmes as $programme) {
      if ((string) $programme['channel'] != (string) $channel['id'])
        continue;
      $start = strtotime(substr($programme['start'], 0, -6));
      $stop = strtotime(substr($programme['stop'], 0, -6));
      $duration = ($stop - $start) / 60;
      // Vérifie si le programme est pour le jour en cours
      if (date('Y-m-d', $start) == date('Y-m-d') && $duration > 20) {
        $fullTitle = @mb_convert_encoding($programme->title, 'UTF-8', 'auto');
        if ($fullTitle === false || mb_check_encoding($fullTitle, 'UTF-8') === false) {
          continue;
        }
        $title = strlen($fullTitle) > 48 ? substr($fullTitle, 0, strrpos(substr($fullTitle, 0, 48), ' ')) . "..." : $fullTitle;
        $formattedDuration = formatDuration2($duration);
        $descfull = @mb_convert_encoding($programme->desc, 'UTF-8', 'auto');
        if ($descfull === false || mb_check_encoding($descfull, 'UTF-8') === false) {
          continue;
        }
        $descfull = strlen($descfull) > 320 ? substr($descfull, 0, strrpos(substr($descfull, 0, 320), ' ')) . "..." : $descfull;
        $descfull = empty($descfull) ? "Sans descriptif" : $descfull;
        echo '<div class="prog" title="' . $descfull . '"><span>' . date("H:i", $start) . ' 🎞️ ' . $title . ' - ' . $programme->category . ' - ' . $formattedDuration . '</span></div>';
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
