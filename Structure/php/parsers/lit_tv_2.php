<?php
function tv($fichier, $choixdebut, $dureemini, $jourprog, $decal, $maxProgrammes){
$xml=new DOMDocument();
@$xml->load($fichier);
$xpath=new DOMXPath($xml);
$chaines=$xpath->query("//channel");
$resultat=[];
$maxprogs=$maxProgrammes * 5;
foreach($chaines as $chaine){
$chaineId=$chaine->getAttribute("id");
$programmes=$xpath->query("//programme[@channel='{$chaineId}']");
$programmesCount=0;
$programmesChaine=[];
$interval=0;
$previousFinTime=null;
do{
$foundProgrammes=false;
foreach($programmes as $programme){
try{if($programmesCount>=$maxprogs){break;}
$debut=$programme->getAttribute("start");
$fin=$programme->getAttribute("stop");
if(!$debut||!$fin){continue;}
$debutTime=strtotime($debut);
$finTime=strtotime($fin);
$duree=($finTime-$debutTime)/60;
if($duree<$dureemini){continue;}
if($programmesCount==0){
if($jourprog=='0'){
$currentDate=date("Ymd");
}else{$currentDate=date("Ymd",strtotime("+$jourprog days"));}
if(strpos($debut,$currentDate)===false){continue;}
$heureDebut=date("Hi",strtotime($debut));
$heureFin=date("Hi",strtotime($fin));
if(($heureDebut>=$choixdebut-30&&$heureDebut&&$duree>=15)||
($heureDebut>=$choixdebut-15&&$heureDebut&&$duree>=5)||
($heureDebut>=$choixdebut&&$heureDebut&&$duree>=$dureemini)){
$previousFinTime=$finTime;
}else{continue;}
}else{if($previousFinTime!==null&&$debutTime<$previousFinTime){continue;}}
$titre=$xpath->query("title",$programme)->item(0);
if(!$titre){continue;}
$titre=$titre->nodeValue;
$image=$xpath->query("icon",$programme)->item(0);
if(!$image){continue;}
$image=$image->getAttribute("src");
$description=$xpath->query("desc",$programme)->item(0);
$description=$description?$description->nodeValue:"Aucune description";
$guestNodes=$programme->getElementsByTagName("guest");
$guests=[];
for($i=0;$i<$guestNodes->length&&$i<1;$i++){$guests[]=$guestNodes->item($i)->nodeValue;}
$actors=[];
$actorNodes=$programme->getElementsByTagName("actor");
for($i=0;$i<$actorNodes->length&&$i<7;$i++){$actors[]=$actorNodes->item($i)->nodeValue;}
$directorNode=$programme->getElementsByTagName("director")->item(0);
$director=$directorNode?$directorNode->nodeValue:null;
$ratingNode=$programme->getElementsByTagName("rating")->item(0);
$rating=$ratingNode?$ratingNode->getElementsByTagName("value")->item(0)->nodeValue:null;
$episodeNode=$programme->getElementsByTagName("episode-num")->item(0);
$episode=null;
if($episodeNode&&preg_match("/^([0-9]+)\.([0-9]+)\.$/",$episodeNode->nodeValue,$matches)){
$episode=str_replace("Episode 0","",str_replace(", épisode 0","",str_replace("Saison 0, é","E","Saison {$matches[1]}, épisode {$matches[2]}")));}
$categories=[];
foreach($xpath->query("category",$programme)as $categoryNode){$categories[]=$categoryNode->nodeValue;}
$categoriesText=implode(", ",$categories);
$programmesChaine[]=[
"titre"=>$titre,
"description"=>$description,
"image"=>$image,
"debut"=>$debut,
"fin"=>$fin,
"duree"=>$duree,
"categories"=>$categoriesText,
"guests"=>$guests,
"actors"=>$actors,
"director"=>$director,
"rating"=>$rating,
"episode"=>$episode];
$foundProgrammes=true;
$programmesCount++;
$previousFinTime=$finTime;
}catch(Exception $e){continue;}}
if(!empty($programmesChaine)){$resultat[$chaine->getElementsByTagName('display-name')->item(0)->nodeValue]=$programmesChaine;}
$interval+=$decal;}
while(!$foundProgrammes&&$interval<=1440);}
return $resultat;}

function dateLabel($date){
$jour=strtotime($date);
$aujourdhui=strtotime(date("Y-m-d"));
$demain=strtotime("+1 day", $aujourdhui);
$hier=strtotime("-1 day", $aujourdhui);
if(date("Y-m-d", $jour)==date("Y-m-d", $aujourdhui)){
return "Aujourd'hui";
}elseif(date("Y-m-d", $jour)==date("Y-m-d", $demain)){
return "Demain";
}elseif(date("Y-m-d", $jour)==date("Y-m-d", $hier)){
return "Hier";
}else{ return date("d/m", $jour);}}

function afficherProgrammeTV($programmes, $maxProgrammes){
$heureActuelle=date("H:i");
foreach($programmes as $chaine=>$programmesChaine){
echo '<div class="tvcontainer"><div class="tvchainetitre">📺&nbsp;'.htmlspecialchars($chaine).'</div>';
$programmeArray=[];$afficher=false;
foreach($programmesChaine as $programme){
if(!isset($programme['debut'])||!isset($programme['fin'])||!isset($programme['titre'])){continue;}
$debut=date("H:i",strtotime($programme['debut']));$fin=date("H:i",strtotime($programme['fin']));
if(!$afficher&&$heureActuelle>=$debut&&$heureActuelle<$fin){$afficher=true;
$programmeArray[]=['heure'=>$debut,'titre'=>htmlspecialchars($programme['titre']),'emote'=>'🏷️','dateLabel'=>dateLabel($programme['debut'])];
}else{$programmeArray[]=['heure'=>$debut,'titre'=>htmlspecialchars($programme['titre']),'emote'=>'','dateLabel'=>dateLabel($programme['debut'])];}}
$programmeArray=array_slice($programmeArray,$afficher?array_search('🏷️',array_column($programmeArray,'emote')):0,$maxProgrammes);
foreach($programmeArray as $programme){
echo '<div class="tvcontainerprog"><div class="tvprog"><span class="tvheure">⏰'.str_replace("🏷️", "", $programme['emote']).'&nbsp;'.$programme['heure'].'</span><span class="tvtitre">🎬&nbsp;'.mb_strimwidth($programme['titre'],0,56,"...").($programme['dateLabel']!=="Aujourd'hui"?' ('.$programme['dateLabel'].')':'').'</span></div></div>';
}echo '</div>';}}
