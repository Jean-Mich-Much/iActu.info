<?php
function getUniqueItems($url)
{
  if (!file_exists($url)) {
    return [];
  }
  $xml = @simplexml_load_file($url);
  usleep(8);
  if ($xml === false) {
    return [];
  }
  $uniqueItems = [];
  $seenLinks = [];
  $seenTitles = [];
  $fallbackItems = [];
  foreach ($xml->channel->item as $item) {
    $title = trim((string) $item->title);
    $link = trim((string) $item->link);
    $pubDate = strtotime((string) $item->pubDate);

    if ($pubDate > time()) {
      $pubDate = strtotime('yesterday');
    }

    $convertedTitle = @mb_convert_encoding($title, 'UTF-8', 'auto');
    if ($convertedTitle === false || mb_check_encoding($convertedTitle, 'UTF-8') === false) {
      continue;
    }
    $title = preg_replace('/\s+/', ' ', $convertedTitle);
    $validLink = filter_var($link, FILTER_VALIDATE_URL) !== false;
    $validDate = $pubDate !== false;
    if (!$validLink && strpos($link, 'http') !== false) {
      $validLink = true;
    }
    if (!$validDate) {
      $pubDate = strtotime('yesterday');
    }
    $key = $title . $link;
    if (!isset($uniqueItems[$key]) && strlen($title) >= 8 && strlen($link) >= 8 && strlen($pubDate) >= 8) {
      if (!in_array($link, $seenLinks) && (!in_array($title, $seenTitles) || strlen($title) <= 16)) {
        $itemData = ['title' => $title, 'link' => $link, 'pubDate' => $pubDate,];
        if ($validLink && $validDate) {
          $uniqueItems[$key] = $itemData;
        } else {
          $fallbackItems[$key] = $itemData;
        }
        $seenLinks[] = $link;
        if (strlen($title) > 16) {
          $seenTitles[] = $title;
        }
      }
    }
  }
  usort($uniqueItems, function ($a, $b) {
    return $b['pubDate'] - $a['pubDate'];
  });
  while (count($uniqueItems) < 20 && !empty($fallbackItems)) {
    $uniqueItems[] = array_shift($fallbackItems);
  }
  return $uniqueItems;
}

function categorizeNews($items)
{
  $today = strtotime('today');
  $todayNews = [];
  $otherNews = [];
  foreach ($items as $item) {
    if ($item['pubDate'] >= $today && count($todayNews) < 20) {
      $todayNews[] = $item;
    } elseif (count($otherNews) < 20) {
      $otherNews[] = $item;
    }
    if (count($todayNews) + count($otherNews) >= 20) {
      break;
    }
  }
  return [$todayNews, $otherNews];
}

function generateUniqueId()
{
  $letters = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 4);
  $numbers = str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
  return $letters . $numbers;
}

function displayNews($news, $category, $isToday = false)
{
  echo '<div class="ay">' . $category . '</div>';
  foreach ($news as $item) {
    $id = generateUniqueId();
    if ($isToday) {
      $id = date('Y-z') . '-' . $id;
    }
    echo '<div class="b"><a href="' . $item['link'] . '" class="n" rel="noopener" id="' . $id . '" target="_blank">' . $item['title'] . '</a></div>';
  }
}

function parsexml($filename)
{
  $url = '_/cache/source/' . $filename . '.xml';
  $savetohtml = '_/cache/html/' . $filename . '.html';
  $savetotoday = '_/cache/html/' . $filename . '.' . date('yz');
  if (file_exists($url)) {
    ob_start();
    $items = getUniqueItems($url);
    usleep(8);
    list($todayNews, $otherNews) = categorizeNews($items);
    displayNews($todayNews, '&#128197;&nbsp;Aujourd’hui', true);
    displayNews($otherNews, '&#128197;&nbsp;Jours précédents');
    echo '<!-- FIN NEWS -->';
    $p = ob_get_clean();
    usleep(8);
    if (
      substr_count($p, '<!-- FIN NEWS -->') === 1 &&
      substr_count($p, 'href="http') >= 20 &&
      strlen($p) >= 512
    ) {
      @file_put_contents($savetohtml, $p);
      usleep(8);
      @chmod($savetohtml, 0775);
      usleep(8);
      if (!empty($todayNews)) {
        @copy($savetohtml, $savetotoday);
        @chmod($savetotoday, 0775);
      }
    }
  }
}

function parse($fichier, $logo, $lienSite, $w, $h, $titreSite,$cache,$source_flux)
{
  if (!file_exists("_/cache/source/".$fichier.".xml") or @filemtime("_/cache/source/".$fichier.".xml") < (time() - $cache)) {
    exec('wget -O "/_/_/cache/source/'.$fichier.'.xml" "'.$source_flux.'" -q --limit-rate=16384k --no-check-certificate'. " > /dev/null &");
   }

  parsexml($fichier);
  $dossier = '_/cache/html/';
  $id = unique(2) . $logo . unique(2);
  $texte1 = ' news aujourd’hui&nbsp;&nbsp;⏰';
  $texte2 = 'Rien de neuf&nbsp;&nbsp;⏰';
  $texte3 = 'Une erreur s’est produite';
  $texte4 = '<div class="ay">&#128197;&nbsp;Aujourd’hui</div><div class="ay">&#128197;&nbsp;Jours précédents</div>';
  $texte5 = '<div class="ay">&#128197;&nbsp;Aujourd’hui : pas de news</div><div class="ay">&#128197;&nbsp;Jours précédents</div>';
  $texte6 = '<div class="ay">&#128197;&nbsp;Jours précédents</div><!-- FIN NEWS -->';
  $texte7 = '<div class="bu">&#128240;&nbsp;C’est tout pour aujourd’hui !</div><!-- FIN NEWS -->';
  ?>

<div class="site">

<div class="conteneur">
 <div class="en_tete">
  <a href="<?php echo $lienSite; ?>" target="_blank" class="lien_site" <?php echo 'id="hfref' . $id . '"'; ?> rel="noopener nofollow" title="Accueil du site">
   <picture>
    <img src="_/img/logos/144px/<?php echo $logo; ?>.webp" srcset="_/img/logos/36px/<?php echo $logo; ?>.webp 1920w, _/img/logos/144px/<?php echo $logo; ?>.webp 7680w" width="<?php echo $w; ?>" height="<?php echo $h; ?>" class="logo_site" alt="Accueil du site" title="Accueil du site" <?php echo 'id="accueilsite' . $id . '"'; ?>>
   </picture>
   <div class="titre_site" <?php echo 'id="titresite' . $id . '"'; ?>><?php echo $titreSite; ?></div>
  </a>

  <div class="news_today">
   <?php
if (file_exists($dossier . $fichier . '.' . date('yz'))) {
$fichiero = @file_get_contents($dossier . $fichier . '.' . date('yz'));
echo substr_count($fichiero, 'id="' . date('Y-z') . '-')." ".$texte1;
} else echo $texte2;
?>
  </div>
 </div>

 <div class="actu">
  <?php
if (file_exists($dossier . $fichier . '.' . date('yz'))) {
$fichiera = @file_get_contents($dossier . $fichier . '.' . date('yz'));
echo str_replace($texte4, $texte5, str_replace($texte6, $texte7, $fichiera));
} else {
$fichierb = @file_get_contents($dossier . $fichier . '.html');
echo str_replace($texte4, $texte5, str_replace($texte6, $texte7, $fichierb));
}
?>
 </div>
 </div>

</div>

<?php
}
?>