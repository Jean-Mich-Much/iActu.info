<?php
function parse($fichier, $logo, $lienSite, $w, $h, $titreSite)
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
  echo '<div class="boite"><div class="enTete"><span class="lienSite"><a href="' . $lienSite . '" target="_blank" rel="noopener nofollow" title="Accueil du site"><picture><img src="structure/img/logos/100px/' . $logo . '.webp" srcset="structure/img/logos/50px/' . $logo . '.webp 1920w, structure/img/logos/100px/' . $logo . '.webp 3840w, structure/img/logos/200px/' . $logo . '.webp 7680w" width="' . $w . '" height="' . $h . '" alt="Accueil du site" title="Accueil du site" id="accueilsite' . $id . '"></picture><span>' . $titreSite . '</span></a></span></div><div class="progtv">';
  foreach ($xml->channel as $channel) {
    echo '<div class="chainetv"><span><span class="nomchainetv">🎥 ' . $channel->{'display-name'} . '</span></span>';
    $progCount = 0;
    $foundPrimeTime = false;
    $timesToCheck = ["20:30", "20:00", "19:00"];
    foreach ($timesToCheck as $time) {
      if (!$foundPrimeTime) {
        foreach ($programmes as $programme) {
          if ((string) $programme['channel'] != (string) $channel['id'])
            continue;
          $start = strtotime(substr($programme['start'], 0, -6));
          $stop = strtotime(substr($programme['stop'], 0, -6));
          $duration = ($stop - $start) / 60;
          if ($start >= strtotime($time) && $progCount < 2 && $duration > 25) {
            $fullTitle = @mb_convert_encoding($programme->title, 'UTF-8', 'auto');
            if ($fullTitle === false || mb_check_encoding($fullTitle, 'UTF-8') === false) {
              continue;
            }
            $title = strlen($fullTitle) > 48 ? substr($fullTitle, 0, strrpos(substr($fullTitle, 0, 48), ' ')) . "..." : $fullTitle;
            $formattedDuration = formatDuration($duration);
            $desc = @mb_convert_encoding($programme->desc, 'UTF-8', 'auto');
            if ($desc === false || mb_check_encoding($desc, 'UTF-8') === false) {
              continue;
            }
            $desc = strlen($desc) > 160 ? substr($desc, 0, strrpos(substr($desc, 0, 160), ' ')) . "..." : $desc;
            $desc = empty($desc) ? "Sans descriptif" : $desc;
            $descfull = @mb_convert_encoding($programme->desc, 'UTF-8', 'auto');
            if ($descfull === false || mb_check_encoding($descfull, 'UTF-8') === false) {
              continue;
            }
            $descfull = strlen($descfull) > 320 ? substr($descfull, 0, strrpos(substr($descfull, 0, 320), ' ')) . "..." : $descfull;
            $descfull = empty($descfull) ? "Sans descriptif" : $descfull;
            $imgSrc = !empty($programme->icon['src']) ? $programme->icon['src'] : "structure/img/vide.webp";
            $fullDesc = strlen($descfull) > 320 ? substr($descfull, 0, strrpos(substr($descfull, 0, 320), ' ')) . "..." : $descfull;
            echo '<div class="prog"><img src="' . $imgSrc . '" width="160" height="90" alt="' . $title . '" id="' . $programme->title . '_prog"><div><div title="' . $fullTitle . '"><span class="heure_debut">' . date("H:i", $start) . '</span> <span class="titre_prog">🎞️ ' . $title . '</span> <span class="categorie">🎬' . $programme->category . '</span> <span class="duree">⏱️Durée : ' . $formattedDuration . '</span></div><span class="descriptif" title="' . $fullDesc . '">' . $desc . '</span></div></div>';
            $progCount++;
            $foundPrimeTime = true;
          }
        }
      }
    }
    if (!$foundPrimeTime) {
      echo 'Programmes répétitifs en continue 😆';
    }
    echo '</div>';
  }
  echo '</div></div>';
}
function formatDuration($duration)
{
  $hours = (int) ($duration / 60);
  $minutes = $duration % 60;
  if ($hours >= 1) {
    return sprintf("%dh%02d", $hours, $minutes);
  } else {
    return sprintf("%d mn", $minutes);
  }
}
