<?php function unique($a)
{
    $b = '';
    $c = array_merge(range(0, 9), range('a', 'z'));
    for ($i = 0; $i < $a; ++$i) {
        $b .= $c[array_rand($c)];
    }

    return $b;
}
  ?>

<?php
function getUniqueItems($url)
{
  if (!file_exists($url)) {
    return [];
  }
  $xml = @simplexml_load_file($url);
  usleep(10);
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
      if (!in_array($link, $seenLinks) && (!in_array($title, $seenTitles) || strlen($title) <= 32)) {
        $itemData = ['title' => $title, 'link' => $link, 'pubDate' => $pubDate,];
        if ($validLink && $validDate) {
          $uniqueItems[$key] = $itemData;
        } else {
          $fallbackItems[$key] = $itemData;
        }
        $seenLinks[] = $link;
        if (strlen($title) > 32) {
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
  $url = 'Structure/cache/source/' . $filename . '.xml';
  $savetohtml = 'Structure/cache/html/' . $filename . '.html';
  $savetotoday = 'Structure/cache/html/' . $filename . '.' . date('yz');
  if (file_exists($url)) {
    ob_start();
    $items = getUniqueItems($url);
    usleep(10);
    list($todayNews, $otherNews) = categorizeNews($items);
    displayNews($todayNews, '&#128197;&nbsp;Aujourd’hui', true);
    displayNews($otherNews, '&#128197;&nbsp;Jours précédents');
    echo '<!-- FIN NEWS -->';
    $p = ob_get_clean();
    usleep(10);
    if (
      substr_count($p, '<!-- FIN NEWS -->') === 1 &&
      substr_count($p, 'href="http') >= 20 &&
      strlen($p) >= 768
    ) {
      @file_put_contents($savetohtml, $p);
      usleep(10);
      @chmod($savetohtml, 0775);
      usleep(10);
      if (!empty($todayNews)) {
        @copy($savetohtml, $savetotoday);
        @chmod($savetotoday, 0775);
      }
    }
  }
}

function parse($fichier, $logo, $lienSite, $w, $h, $titreSite,$cache,$source_flux)
{
  if (!file_exists("Structure/cache/source/".$fichier.".xml") or @filemtime("Structure/cache/source/".$fichier.".xml") < (time() - $cache)) {
    exec('wget -O "/_/Structure/cache/source/'.$fichier.'.xml" "'.$source_flux.'" -q --limit-rate=16384k --no-check-certificate'. " > /dev/null &");
   }

  parsexml($fichier);
  $dossier = 'Structure/cache/html/';
  $id = unique(2) . $fichier . unique(2);
  $texte1 = ' news aujourd’hui&nbsp;&nbsp;⏰';
  $texte2 = 'Rien de neuf&nbsp;&nbsp;⏰';
  $texte3 = '<div class="ay">&#128197;&nbsp;Aujourd’hui</div><div class="ay">&#128197;&nbsp;Jours précédents</div>';
  $texte4 = '<div class="ay">&#128197;&nbsp;Aujourd’hui : pas de news</div><div class="ay">&#128197;&nbsp;Jours précédents</div>';
  $texte5 = '<div class="ay">&#128197;&nbsp;Jours précédents</div><!-- FIN NEWS -->';
  $texte6 = '<div class="bu">&#128240;&nbsp;C’est tout pour aujourd’hui !</div><!-- FIN NEWS -->';
  ?>

<div class="site">

 <div class="site_conteneur pad16px">
  <div class="site_en_tete">
   <a href="<?php echo $lienSite; ?>" target="_blank" class="lien_site" <?php echo 'id="hfref' . $id . '"'; ?> rel="noopener nofollow" title="Accueil du site">
    <span class="site_nom_symbole"><?php echo $logo; ?></span>
    <span class="site_nom" <?php echo 'id="titresite' . $id . '"'; ?>><?php echo $titreSite; ?></span>
   </a>

   <span class="site_nbre_news">
    <?php
if (file_exists($dossier . $fichier . '.' . date('yz'))) {
$fichiero = @file_get_contents($dossier . $fichier . '.' . date('yz'));
echo substr_count($fichiero, 'id="' . date('Y-z') . '-')." ".$texte1;
} else echo $texte2;
?>
   </span>
  </div>

  <div class="site_news">
   <?php
if (file_exists($dossier . $fichier . '.' . date('yz'))) {
$fichiera = @file_get_contents($dossier . $fichier . '.' . date('yz'));
echo str_replace($texte3, $texte4, str_replace($texte5, $texte6, $fichiera));
} else {
$fichierb = @file_get_contents($dossier . $fichier . '.html');
echo str_replace($texte3, $texte4, str_replace($texte5, $texte6, $fichierb));
}
?>
  </div>
 </div>

</div>

<?php
}
?>