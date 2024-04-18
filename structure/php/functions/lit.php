<?php
function getUniqueItems($url)
{
  if (!file_exists($url)) {
    return [];
  }
  $xml = @simplexml_load_file($url);
  usleep(200);
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
  $url = 'News/copy/' . $filename . '.xml';
  $savetohtml = 'News/html/' . $filename . '.html';
  $savetotoday = 'News/html/' . $filename . '.' . date('yz');
  if (file_exists($url)) {
    ob_start();
    $items = getUniqueItems($url);
    usleep(200);
    list($todayNews, $otherNews) = categorizeNews($items);
    displayNews($todayNews, 'Aujourd’hui', true);
    displayNews($otherNews, 'Jours précédents');
    echo '<!-- FIN NEWS -->';
    $p = ob_get_clean();
    usleep(100);
    if (
      substr_count($p, '<!-- FIN NEWS -->') === 1 &&
      substr_count($p, 'href="http') >= 20 &&
      strlen($p) >= 768
    ) {
      file_put_contents($savetohtml, $p);
      usleep(200);
      @chmod($savetohtml, 0775);
      usleep(200);
      if (!empty($todayNews)) {
        @copy($savetohtml, $savetotoday);
        @chmod($savetotoday, 0775);
      }
    }
  } else {
    file_put_contents('News/copy/' . $filename . '.error', "Bug in the matrix: We've lost the file, Mr. Anderson!");
  }
}

function parse($fichier, $logo, $lienSite, $w, $h, $titreSite)
{
  parsexml($fichier);
  $dossier = 'News/html/';
  $id = unique(2) . $logo . unique(2);
  $icon = 'structure/img/icoHorloge2x.webp" width="100" height="100" srcset="structure/img/icoHorloge1x.webp 1920w, structure/img/icoHorloge2x.webp 3840w';
  $texte1 = ' news aujourd’hui<span><img src="' . $icon . '" alt="Pages" id="icon' . $id . '"></picture></span></span>';
  $texte2 = '<span>Rien de neuf<span><img src="' . $icon . '" alt="Pages" id="icon' . $id . '"></picture></span></span>';
  $texte3 = '<span>Une erreur s’est produite<span><img src="' . $icon . '" alt="Pages" id="icon' . $id . '"></picture></span></span>';
  $texte4 = '<div class="ay">Aujourd’hui</div><div class="ay">Jours précédents</div>';
  $texte5 = '<div class="ay">Aujourd’hui : pas de news</div><div class="ay">Jours précédents</div>';
  $texte6 = '<div class="ay">Jours précédents</div><!-- FIN NEWS -->';
  $texte7 = '<div class="bu">C’est tout pour aujourd’hui !</div><!-- FIN NEWS -->';
  ?>
  <div class="boite">
    <div class="enTete" <?php echo 'id="entete' . $id . '"'; ?>>
      <span class="lienSite" <?php echo 'id="liensite' . $id . '"'; ?>>
        <a href="<?php echo $lienSite; ?>" target="_blank" <?php echo 'id="hfref' . $id . '"'; ?> rel="noopener nofollow" title="Accueil du site">
          <picture>
            <img src="structure/img/logos/100px/<?php echo $logo; ?>.webp" srcset="structure/img/logos/50px/<?php echo $logo; ?>.webp 1920w, structure/img/logos/100px/<?php echo $logo; ?>.webp 3840w, structure/img/logos/200px/<?php echo $logo; ?>.webp 7680w" width="<?php echo $w; ?>" height="<?php echo $h; ?>" alt="Accueil du site" title="Accueil du site" <?php echo 'id="accueilsite' . $id . '"'; ?>>
          </picture>
          <span <?php echo 'id="titresite' . $id . '"'; ?>><?php echo $titreSite; ?></span>
        </a>
      </span><span class="majSite" <?php echo 'id="majsite' . $id . '"'; ?>><span <?php echo 'id="compter' . $id . '"'; ?>>
          <?php
          $found = false;
          for ($i = 0; $i <= 38; $i++) {
            if (file_exists($dossier . $fichier . '.' . date('yz', strtotime("-$i days")))) {
              if ($i == 0) {
                $fichiero = file_get_contents($dossier . $fichier . '.' . date('yz'));
                echo '<span>';
                echo substr_count($fichiero, 'id="' . date('Y-z') . '-');
                echo $texte1;
              } else {
                echo $texte2;
              }
              $found = true;
              break;
            }
          }
          if (!$found) {
            echo $texte3;
          }
          ?>
        </span></span>
    </div>
    <span class="actu" <?php echo 'id="actu' . $id . '"'; ?>>
      <?php
      if (file_exists($dossier . $fichier . '.' . date('yz'))) {
        $fichiera = file_get_contents($dossier . $fichier . '.' . date('yz'));
        echo str_replace($texte4, $texte5, str_replace($texte6, $texte7, $fichiera));
      } else {
        $fichierb = file_get_contents($dossier . $fichier . '.html');
        echo str_replace($texte4, $texte5, str_replace($texte6, $texte7, $fichierb));
      }
      ?>
    </span>
  </div>

<?php
}
?>