<?php
$titre_page='Tv';
$nom_page='theme_02_tv';
$nom_page_theme_alternatif='tv.php';
$page_active='tv';
$page_tv_active= 'cesoir';
$titre_page_active='&#129302;&nbsp;Tv';
$cache_s=45;
$theme='01';

if(!file_exists($nom_page.'.html')||filemtime($nom_page.'.html')<(time()-$cache_s)||!file_exists($nom_page.date("j").'.html')){ob_start();?>

<!DOCTYPE html>
<html lang="fr">

 <head><?php @include "Structure/php/modules/header.php"; ?><style type="text/css">
  <?php @readfile("Structure/css/fonts.css");
  @readfile("Structure/css/base.css");
  @readfile("Structure/css/". $theme . ".css");
  ?>
  </style>
 </head>

 <body id="body" lang="fr">
  <div class="flex-page">
  <div class="menu"><?php @include "Structure/php/modules/menu_theme_02.php"; ?></div>
  <div class="menu"><?php @include "Structure/php/modules/menu_tv.php"; ?></div>
   <div class="mid">
    <?php $xmlFile = "Structure/cache/tv/xmltv_tnt.xml";
$tem="Structure/cache/tv/xmltv_tnt".date("j").".tem";
$cacheSTv = 18000;
if (
    !file_exists($xmlFile) ||
    @filemtime($tem) < time() - $cacheSTv
) {
    exec(
        'wget -O "/_/Structure/cache/tv/xmltv_tnt.xml" "https://xmltvfr.fr/xmltv/xmltv_tnt.xml" -q --no-check-certificate'
    );
    if (!file_exists("Structure/cache/tv/xmltv_tnt.time")) {
        fopen("Structure/cache/tv/xmltv_tnt.time", "w");
    }
    @copy(
        "Structure/cache/tv/xmltv_tnt.time",
        $tem
    );
}
$xml = new DOMDocument();
if (!$xml->load($xmlFile)) {
    die("Erreur de chargement du fichier XML");
}
$xp = new DOMXPath($xml);
$chs = $xp->query("/tv/channel");
$td = (new DateTime())->format("Y-m-d");
$tm = (new DateTime())->modify("+1 day")->format("Y-m-d") . " 06:00:00";
function getCatEmoji($cat)
{
    $e = [
        "Film" => "🎞️ Film",
        "Mag" => "📰 Mag",
        "Jeunesse" => "🧸 Jeunesse",
        "Info" => "ℹ️ Info",
        "Div" => "🎉 Div",
        "Prog" => "📺 Prog",
        "Var" => "🎶 Var",
        "Spec" => "🎭 Spec",
        "PopRock" => "🎸 PopRock",
        "default" => "📺 Autre",
    ];
    return $e[$cat] ?? $e["default"];
}

foreach($chs as $ch){$chId=$ch->getAttribute("id");$dispName=$ch->getElementsByTagName("display-name")[0]->nodeValue;$ico=$ch->getElementsByTagName("icon")[0]->getAttribute("src");echo "<div style='background-color: var(--c8);color: var(--c2);font-synthesis-small-caps:auto;font-synthesis-weight:auto;font-synthesis:style;font-variant-emoji:emoji;font-variant:discretionary-ligatures tabular-nums;outline:round(calc(4 / 1920 * 100vw),1px) solid var(--c7);outline-offset:round(calc(-3 / 1920 * 100vw),1px);border-radius:round(calc(12 / 1920 * 100vw),1px);padding:round(up,calc(8 / 1920 * 100vw),1px); margin-bottom:round(up,calc(6 / 1920 * 100vw),1px);'>";echo "<div class='f20px' style='border-bottom:round(up,calc(1 / 1920 * 100vw),1px) solid var(--c7);margin:round(up,calc(6 / 1920 * 100vw),1px) round(calc(1 / 1920 * 100vw),1px) 0 auto;padding:round(up,calc(6 / 1920 * 100vw),1px) round(calc(6 / 1920 * 100vw),1px) round(up,calc(12 / 1920 * 100vw),1px) round(calc(6 / 1920 * 100vw),1px);color: var(--c10);font-variation-settings: \"wght\" 600;'>📺&nbsp;$dispName</div>";$progs=$xp->query("/tv/programme[@channel='$chId']");
    
    $ptStart=new DateTime("$td 20:49:59",new DateTimeZone("Europe/Paris"));
    $ptEnd=new DateTime("$td 23:59:59",new DateTimeZone("Europe/Paris"));
    
    $firstPT=null;$secondPT=null;$shown=[];foreach($progs as $p){$start=new DateTime($p->getAttribute("start"),new DateTimeZone("Europe/Paris"));$stop=new DateTime($p->getAttribute("stop"),new DateTimeZone("Europe/Paris"));$durMin=($stop->getTimestamp()-$start->getTimestamp())/60;
        
        if($start>=$ptStart&&$start<$ptEnd&&$durMin>35){
            
            if(!$firstPT){$firstPT=$p;}elseif($start>new DateTime($firstPT->getAttribute("stop"),new DateTimeZone("Europe/Paris"))&&$durMin>25){$secondPT=$p;break;}}}if($firstPT){$firstStop=new DateTime($firstPT->getAttribute("stop"),new DateTimeZone("Europe/Paris"));
                
                $secondPTStart=($firstStop>$ptEnd)?$firstStop:new DateTime("$td 21:29:59",new DateTimeZone("Europe/Paris"));
                $secondPTEnd=new DateTime("$td 23:59:59",new DateTimeZone("Europe/Paris"));
                
foreach($progs as $p){$start=new DateTime($p->getAttribute("start"),new DateTimeZone("Europe/Paris"));$stop=new DateTime($p->getAttribute("stop"),new DateTimeZone("Europe/Paris"));$durMin=($stop->getTimestamp()-$start->getTimestamp())/60;if($start>=$secondPTStart&&$start<=$secondPTEnd&&$durMin>25){if(!$secondPT){$secondPT=$p;}elseif($start>$firstStop&&$start>=$secondPTStart&&$durMin>25){$secondPT=$p;break;}}}if(!$secondPT){foreach($progs as $p){$start=new DateTime($p->getAttribute("start"),new DateTimeZone("Europe/Paris"));$stop=new DateTime($p->getAttribute("stop"),new DateTimeZone("Europe/Paris"));$durMin=($stop->getTimestamp()-$start->getTimestamp())/60;if($start>$firstStop&&$durMin>35&&!in_array($p->getAttribute("start").$p->getAttribute("stop").$p->getElementsByTagName("title")[0]->nodeValue,$shown)){$secondPT=$p;break;}}}}foreach([$firstPT,$secondPT]as $p){if($p&&!in_array($p->getAttribute("start").$p->getAttribute("stop").$p->getElementsByTagName("title")[0]->nodeValue,$shown)){$shown[]=$p->getAttribute("start").$p->getAttribute("stop").$p->getElementsByTagName("title")[0]->nodeValue;$start=new DateTime($p->getAttribute("start"),new DateTimeZone("Europe/Paris"));$stop=new DateTime($p->getAttribute("stop"),new DateTimeZone("Europe/Paris"));$title=$p->getElementsByTagName("title")[0]->nodeValue;$cat=$p->getElementsByTagName("category")[0]->nodeValue??"default";$catEmoji=getCatEmoji($cat);$dur=$start->diff($stop);$desc=$p->getElementsByTagName("desc")[0]->nodeValue??"";$ico=$p->getElementsByTagName("icon")[0]->getAttribute("src")??"";echo "<div style='display:flex;flex-direction:column;width:100%;padding: round(calc(8 / 1920 * 100vw), 1px) round(calc(8 / 1920 * 100vw), 1px) round(calc(12 / 1920 * 100vw), 1px) round(calc(8 / 1920 * 100vw), 1px);margin:round(up,calc(4 / 1920 * 100vw),1px) round(calc(1 / 1920 * 100vw),1px) round(up,calc(4 / 1920 * 100vw),1px) auto;border-bottom:round(up,calc(1 / 1920 * 100vw),1px) solid var(--c7);'>";echo "<div style='align-self:flex-start;margin-bottom: round(calc(8 / 1920 * 100vw), 1px);margin-top: round(calc(1 / 1920 * 100vw), 1px);'><strong class='f18px' style='color: var(--c5);font-variation-settings: \"wght\" 500;'>🎬&nbsp;$title</strong></div>";echo "<div style='align-self:flex-start;'>⌚&nbsp;{$start->format("H:i")}&nbsp;-&nbsp;$catEmoji</div>";echo "<div style='align-self:flex-start;'><img src='$ico' alt='$title' style='outline: round(calc(3 / 1920 * 100vw), 1px) solid var(--c8);outline-offset: round(calc(-3 / 1920 * 100vw), 1px);border-radius: round(calc(12 / 1920 * 100vw), 1px);margin: round(calc(8 / 1920 * 100vw), 1px) 0;min-width:auto;min-height: auto;max-width: 75%;max-height: round(calc(320 / 1920 * 100vw), 1px);width: auto;height: auto;'></div>";echo "<div><strong style='line-height: round(calc(24 / 1920 * 100vw), 1px);'>⏰&nbsp;</strong>{$dur->h}h{$dur->i}min<br><strong>📜&nbsp;</strong>$desc</div></div>";}}echo "</div>";};?>
   </div>
  </div>

  <div class="messages retour_ligne_on hauteur_auto"><?php @include 'Structure/php/modules/messages.php';?></div>
  <div class="menu bot"><?php @include "Structure/php/modules/menu_tv.php"; ?></div>
  <div class="menu bot"><?php @include 'Structure/php/modules/menu_theme_02.php';?></div>
 </body>

</html>
<?php $p=ob_get_clean();if(substr_count($p,'<!DOCTYPE html>')===1&&substr_count($p,'</html>')===1&&strlen($p)>=1024){echo $p;@file_put_contents($nom_page.'.html',$p);@chmod($nom_page.'.html',0775);@copy($nom_page.'.html',$nom_page.date("j").'.html');@chmod($nom_page.date("j").'.html',0775);}else{@readfile($nom_page.'.html');}}else{@readfile($nom_page.'.html');};