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
for($i=0;$i<$actorNodes->length&&$i<7;$i++)
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
echo '<div class="tvcontainer"><div class="f20px">📺&nbsp;'.htmlspecialchars($chaine).'</div>';
foreach($programmesChaine as $programme)
{
echo '<div class="tvprogramme"><div class="tvprogtitre"><strong class="f18px">🎬&nbsp;'.htmlspecialchars($programme['titre']).'</strong></div><div class="tvproginfos">⏰&nbsp;'.date("H:i",strtotime($programme['debut'])).'&nbsp;&nbsp;⏱️&nbsp;'.$programme['duree'].' mn&nbsp;&nbsp;💤&nbsp;Finit à '.date("H:i",strtotime($programme['fin'])).'&nbsp;'.str_replace("&nbsp;🍿&nbsp;Aucun genre, Aucun sous-genre", "", '&nbsp;🍿&nbsp;'.htmlspecialchars($programme['categories'])).'</div><div class="tvgrid"><img class="tvimage" src="'.htmlspecialchars($programme['image']).'" alt="'.htmlspecialchars($programme['titre']).'"><span class="tvdescription"><span class="tvdescplus">'.str_replace("📜&nbsp;Aucune description", "", '📜&nbsp;'.mb_strimwidth(htmlspecialchars($programme['description']), 0, 376, "...")).'</span>';
if($programme['director']) { echo '<span class="tvdescplus">&nbsp;🎬&nbsp;Réalisé par&nbsp;'.$programme['director'].'</span>'; }
if(!empty($programme['actors'])) { echo '<span class="tvdescplus">&nbsp;🎭&nbsp;Acteurs:&nbsp;'.implode(", ",$programme['actors']).'</span>'; }
if(!empty($programme['guests'])) { echo '<span class="tvdescplus">&nbsp;👤&nbsp;Production&nbsp;&#47;&nbsp;invité&nbsp;&#47;&nbsp;autre&nbsp;:&nbsp;'.implode(", ",$programme['guests']).'</span>'; }
if($programme['rating']) { echo '<span class="tvdescplus">&nbsp;👀&nbsp;Contenu&nbsp;:&nbsp;'.str_replace("Tout public ans", "tout public", str_replace("-Tout public", "Tout public", $programme['rating']).' ans').'</span>'; }
if($programme['episode']) { echo '<span class="tvdescplus">&nbsp;📺&nbsp;'.$programme['episode'].'</span>'; }
echo '</span></div></div>';
}
echo '</div>';
}};