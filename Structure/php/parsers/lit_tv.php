<?php
function tv($fichier,$choixdebut,$choixfin,$dureemini,$jourprog,$avantapres,$decal,$maxProgrammes)
{
$xml=new DOMDocument();
@$xml->load($fichier);
$xpath=new DOMXPath($xml);
$chaines=$xpath->query("//channel");
$resultat=[];
foreach($chaines as $chaine)
{
$chaineId=$chaine->getAttribute("id");
$programmes=$xpath->query("//programme[@channel='{$chaineId}']");
$programmesCount=0;
$programmesChaine=[];
$interval=0;
do
{
$foundProgrammes=false;
foreach($programmes as $programme)
{
try
{
if($programmesCount>=$maxProgrammes)
{
break;
}
$debut=$programme->getAttribute("start");
$fin=$programme->getAttribute("stop");
if(!$debut||!$fin)
{
continue;
}
$debutTime=strtotime($debut);
$finTime=strtotime($fin);
$duree=($finTime-$debutTime)/60;
if($duree<$dureemini)
{
continue;
}
$titre=$xpath->query("title",$programme)->item(0);
if(!$titre)
{
continue;
}
$titre=$titre->nodeValue;
$image=$xpath->query("icon",$programme)->item(0);
if(!$image)
{
continue;
}
$image=$image->getAttribute("src");
$description=$xpath->query("desc",$programme)->item(0);
$description=$description?$description->nodeValue:"Aucune description";
$guestNodes=$programme->getElementsByTagName("guest");
$guests=[];
for($i=0;$i<$guestNodes->length&&$i<1;$i++)
{
$guests[]=$guestNodes->item($i)->nodeValue;
}
$actors=[];
$actorNodes=$programme->getElementsByTagName("actor");
for($i=0;$i<$actorNodes->length&&$i<5;$i++)
{
$actors[]=$actorNodes->item($i)->nodeValue;
}
$directorNode=$programme->getElementsByTagName("director")->item(0);
$director=$directorNode?$directorNode->nodeValue:null;
$ratingNode=$programme->getElementsByTagName("rating")->item(0);
$rating=$ratingNode?$ratingNode->getElementsByTagName("value")->item(0)->nodeValue:null;
$episodeNode=$programme->getElementsByTagName("episode-num")->item(0);
$episode=null;
if($episodeNode && preg_match("/^([0-9]+)\.([0-9]+)\.$/", $episodeNode->nodeValue, $matches))
{
$episode=str_replace("Episode 0", "", str_replace(", épisode 0", "", str_replace("Saison 0, é", "E", "Saison {$matches[1]}, épisode {$matches[2]}")));
}
if($jourprog=='0')
{
$currentDate=date("Ymd");
}
else
{
$currentDate=date("Ymd",strtotime("+$jourprog days"));
}
if(strpos($debut,$currentDate)===false)
{
continue;
}
$heureDebut=date("Hi",strtotime($debut));
$heureFin=date("Hi",strtotime($fin));
if(($avantapres=='avant' && ($heureDebut<$choixdebut-$interval || $heureFin>$choixfin-$interval)) || ($avantapres=='apres' && ($heureDebut<$choixdebut+$interval || $heureFin>$choixfin+$interval)))
{
continue;
}
$categories=[];
foreach($xpath->query("category",$programme) as $categoryNode)
{
$categories[]=$categoryNode->nodeValue;
}
$categoriesText=implode(", ",$categories);
$programmesChaine[]=["titre"=>$titre,"description"=>$description,"image"=>$image,"debut"=>$debut,"fin"=>$fin,"duree"=>$duree,"categories"=>$categoriesText,"guests"=>$guests,"actors"=>$actors,"director"=>$director,"rating"=>$rating,"episode"=>$episode];
$foundProgrammes=true;
$programmesCount++;
}
catch(Exception $e)
{
continue;
}
}
if(!empty($programmesChaine))
{
$resultat[$chaine->getElementsByTagName('display-name')->item(0)->nodeValue]=$programmesChaine;
}
$interval+=$decal;
}
while(!$foundProgrammes && $interval<=1440);
}
return $resultat;
}

function afficherProgrammeTV($programmes)
{
foreach($programmes as $chaine=>$programmesChaine)
{
echo '<div style="background-color: var(--c8);color: var(--c2);font-synthesis-small-caps:auto;font-synthesis-weight:auto;font-synthesis:style;font-variant-emoji:emoji;font-variant:discretionary-ligatures tabular-nums;outline:round(calc(4 / 1920 * 100vw),1px) solid var(--c7);outline-offset:round(calc(-3 / 1920 * 100vw),1px);border-radius:round(calc(12 / 1920 * 100vw),1px);padding:round(up,calc(8 / 1920 * 100vw),1px); margin-bottom:round(up,calc(6 / 1920 * 100vw),1px);"><div class="f20px" style="border-bottom:round(up,calc(1 / 1920 * 100vw),1px) solid var(--c7);margin:round(up,calc(6 / 1920 * 100vw),1px) round(calc(1 / 1920 * 100vw),1px) 0 auto;padding:round(up,calc(6 / 1920 * 100vw),1px) round(calc(6 / 1920 * 100vw),1px) round(up,calc(12 / 1920 * 100vw),1px) round(calc(6 / 1920 * 100vw),1px);color: var(--c10);font-variation-settings: &quot;wght&quot; 600;">📺&nbsp;'.htmlspecialchars($chaine).'</div>';
  foreach($programmesChaine as $programme)
{
echo '<div style="display:flex;flex-direction:column;width:100%;padding: round(calc(8 / 1920 * 100vw), 1px) round(calc(8 / 1920 * 100vw), 1px) round(calc(12 / 1920 * 100vw), 1px) round(calc(8 / 1920 * 100vw), 1px);margin:round(up,calc(4 / 1920 * 100vw),1px) round(calc(1 / 1920 * 100vw),1px) round(up,calc(4 / 1920 * 100vw),1px) auto;border-bottom:round(up,calc(1 / 1920 * 100vw),1px) solid var(--c7);"><div style="align-self:flex-start;margin-bottom: round(calc(8 / 1920 * 100vw), 1px);margin-top: round(calc(1 / 1920 * 100vw), 1px);"><strong class="f18px" style="color: var(--c5);font-variation-settings: &quot;wght&quot; 500;">🎬&nbsp;'.htmlspecialchars($programme['titre']).'</strong></div><div style="align-self:flex-start;">⏰&nbsp;'.date("H:i",strtotime($programme['debut'])).'&nbsp;⏱️&nbsp;'.$programme['duree'].' mn&nbsp;💤&nbsp;Finit à '.date("H:i",strtotime($programme['fin'])).str_replace("&nbsp;🍿&nbsp;Aucun genre, Aucun sous-genre", "", '&nbsp;🍿&nbsp;'.htmlspecialchars($programme['categories'])).'</div><div style="align-self:stretch;display: grid;grid-template-columns: 0.6fr 1fr; grid-template-rows: 1fr; gap: 0px 0px; grid-template-areas: ". ."; justify-content: start; align-content: start; justify-items: start; align-items: start;"><img src="'.htmlspecialchars($programme['image']).'" alt="'.htmlspecialchars($programme['titre']).'" style="outline: round(calc(2 / 1920 * 100vw), 1px) solid var(--c6);outline-offset: round(calc(-2 / 1920 * 100vw), 1px);border-radius: round(calc(6 / 1920 * 100vw), 1px);margin: round(calc(6 / 1920 * 100vw), 1px) 0 round(calc(4 / 1920 * 100vw), 1px) 0;min-width:auto;min-height: auto;max-width: 100%;max-height: round(calc(320 / 1920 * 100vw), 1px);width: 100%;height: auto;"><span style="align-self:stretch;padding: round(calc(12 / 1920 * 100vw), 1px);">'.str_replace("📜&nbsp;Aucune description", "", '📜&nbsp;'.mb_strimwidth(htmlspecialchars($programme['description']), 0, 400, "..."));
if(!empty($programme['guests'])) { echo '&nbsp;👤&nbsp;'.implode(", ",$programme['guests']); }
if(!empty($programme['actors'])) { echo '&nbsp;🎭&nbsp;Acteurs: '.implode(", ",$programme['actors']); }
if($programme['director']) { echo '&nbsp;🎬&nbsp;Réalisé par '.$programme['director']; }
if($programme['rating']) { echo '&nbsp;🚸&nbsp;Csa&nbsp;'.str_replace("-", "", $programme['rating']); }
if($programme['episode']) { echo '&nbsp;📺&nbsp;'.$programme['episode']; }
echo '</span></div></div>';
  };
  echo '</div>';
 };
};