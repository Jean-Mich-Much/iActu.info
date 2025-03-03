<?php
function tv($fichier, $choixdebut, $choixfin, $dureemini, $jourprog, $decal, $maxProgrammes){
 $xml=new DOMDocument();
@$xml->load($fichier);
$xpath=new DOMXPath($xml);
$chaines=$xpath->query("//channel");
$resultat=[];
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
try{
if($programmesCount>=$maxProgrammes){
break;
}
$debut=$programme->getAttribute("start");
$fin=$programme->getAttribute("stop");
if(!$debut||!$fin){
continue;
}
$debutTime=strtotime($debut);
$finTime=strtotime($fin);
$duree=($finTime-$debutTime)/60;
if($duree<$dureemini){
continue;
}
if($programmesCount==0){ 
// Appliquer les limites au premier programme
if($jourprog=='0'){
$currentDate=date("Ymd");
}else{
$currentDate=date("Ymd",strtotime("+$jourprog days"));
}
if(strpos($debut,$currentDate)===false){
continue;
}
$heureDebut=date("Hi",strtotime($debut));
$heureFin=date("Hi",strtotime($fin));
if(
 ($heureDebut>=$choixdebut-512&&$heureDebut<=$choixfin&&$duree>=256)||
 ($heureDebut>=$choixdebut-256&&$heureDebut<=$choixfin&&$duree>=128)||
 ($heureDebut>=$choixdebut-128&&$heureDebut<=$choixfin&&$duree>=64)||
 ($heureDebut>=$choixdebut-64&&$heureDebut<=$choixfin&&$duree>=32)||
 ($heureDebut>=$choixdebut-32&&$heureDebut<=$choixfin&&$duree>=16)||
 ($heureDebut>=$choixdebut-16&&$heureDebut<=$choixfin&&$duree>=8)||
 ($heureDebut>=$choixdebut-8&&$heureDebut<=$choixfin&&$duree>=4)||
 ($heureDebut>=$choixdebut-4&&$heureDebut<=$choixfin&&$duree>=2)||
($heureDebut>=$choixdebut&&$heureDebut<=$choixfin&&$duree>=$dureemini)){
$previousFinTime=$finTime;
}else{
continue;
}
}else{
// Ne pas mettre de limites au programme suivant tant qu'il fait suite au premier programme
if($previousFinTime!==null&&$debutTime<$previousFinTime){
continue;
}
}
$titre=$xpath->query("title",$programme)->item(0);
if(!$titre){
continue;
}
$titre=$titre->nodeValue;
$image=$xpath->query("icon",$programme)->item(0);
if(!$image){
continue;
}
$image=$image->getAttribute("src");
$description=$xpath->query("desc",$programme)->item(0);
$description=$description?$description->nodeValue:"Aucune description";
$guestNodes=$programme->getElementsByTagName("guest");
$guests=[];
for($i=0;$i<$guestNodes->length&&$i<1;$i++){
$guests[]=$guestNodes->item($i)->nodeValue;
}
$actors=[];
$actorNodes=$programme->getElementsByTagName("actor");
for($i=0;$i<$actorNodes->length&&$i<7;$i++){
$actors[]=$actorNodes->item($i)->nodeValue;
}
$directorNode=$programme->getElementsByTagName("director")->item(0);
$director=$directorNode?$directorNode->nodeValue:null;
$ratingNode=$programme->getElementsByTagName("rating")->item(0);
$rating=$ratingNode?$ratingNode->getElementsByTagName("value")->item(0)->nodeValue:null;
$episodeNode=$programme->getElementsByTagName("episode-num")->item(0);
$episode=null;
if($episodeNode&&preg_match("/^([0-9]+)\.([0-9]+)\.$/",$episodeNode->nodeValue,$matches)){
$episode=str_replace("Episode 0","",str_replace(", épisode 0","",str_replace("Saison 0, é","E","Saison {$matches[1]}, épisode {$matches[2]}")));
}
$categories=[];
foreach($xpath->query("category",$programme)as $categoryNode){
$categories[]=$categoryNode->nodeValue;
}
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
"episode"=>$episode
];
$foundProgrammes=true;
$programmesCount++;
$previousFinTime=$finTime;
}catch(Exception $e){
continue;
}
}
if(!empty($programmesChaine)){
$resultat[$chaine->getElementsByTagName('display-name')->item(0)->nodeValue]=$programmesChaine;
}
$interval+=$decal;
}
while(!$foundProgrammes&&$interval<=1440);
}
return $resultat;
}
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
 }else{
 return date("d/m", $jour);
 }} 
function afficherProgrammeTV($programmes){
foreach($programmes as $chaine=>$programmesChaine){
echo '<div class="tvcontainer"><div class="tvchainetitre">📺&nbsp;'.htmlspecialchars($chaine).'</div>';
foreach($programmesChaine as $programme){
echo '<div class="tvcontainerprog"><div class="tvprog"><span class="tvheure">⏰&nbsp;'.date("H:i",strtotime($programme['debut'])).'</span><span class="tvtitre">🎬&nbsp;'.mb_strimwidth(htmlspecialchars($programme['titre']),0,56,"...").(dateLabel($programme['debut']) !== "Aujourd'hui" ? ' ('.dateLabel($programme['debut']).')' : '').'</span></div>';
// if($programme['duree']){echo '<span class="tvplus">&nbsp;⏱️&nbsp;'.$programme['duree'].'&nbsp;mn&nbsp; </span>';}
// if($programme['categories']){echo '<span class="tvplus">'.str_replace("&nbsp;🍿&nbsp;indéterminé","",str_replace("&nbsp;🍿&nbsp;Aucun genre, Aucun sous-genre","",'&nbsp;🍿&nbsp;'.str_replace("Programme ","",str_replace("Programme, ","",htmlspecialchars($programme['categories']))))).'&nbsp; </span>';}
// if($programme['episode']){echo '<span class="tvplus">&nbsp;📺&nbsp;'.$programme['episode'].'&nbsp; </span>';}
// if($programme['image']) { echo '<span class="tvplus"><img class="tvimage" src="'.htmlspecialchars($programme['image']).'" alt="'.htmlspecialchars($programme['titre']).'"></span>'; }
// if($programme['description']) { echo '<span class="tvplus">'.str_replace("📜&nbsp;Aucune description","",'📜&nbsp;'.mb_strimwidth(htmlspecialchars($programme['description']),0,304,"...")).'&nbsp; </span>'; }
// if($programme['director']) { echo '<span class="tvplus">&nbsp;🎬&nbsp;Réalisé par&nbsp;'.$programme['director'].'&nbsp; </span>'; }
// if(!empty($programme['actors'])) { echo '<span class="tvplus">&nbsp;🎭&nbsp;Acteurs:&nbsp;'.implode(", ",$programme['actors']).'&nbsp; </span>'; }
// if(!empty($programme['guests'])) { echo '<span class="tvplus">&nbsp;👤&nbsp;Production&nbsp;&#47;&nbsp;invité&nbsp;&#47;&nbsp;autre&nbsp;:&nbsp;'.implode(", ",$programme['guests']).'</span>'; }
// if($programme['rating']) { echo '<span class="tvplus">&nbsp;👀&nbsp;Contenu&nbsp;:&nbsp;'.str_replace("Tout public ans", "tout public", str_replace("-Tout public", "Tout public", $programme['rating']).' ans').'&nbsp; </span>'; }
echo '</div>';
}
echo '</div>';
}};
