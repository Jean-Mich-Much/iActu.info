<?php
function tv($fichier, $choixdebut, $dureemini, $jourprog, $decal, $maxProgrammes){
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
break;}
$debut=$programme->getAttribute("start");
$fin=$programme->getAttribute("stop");
if(!$debut||!$fin){continue;}
$debutTime=strtotime($debut);
$finTime=strtotime($fin);
$duree=($finTime-$debutTime)/60;
if($duree<$dureemini){continue;}
if($programmesCount==0){ 
// Appliquer les limites au premier programme
if($jourprog=='0'){
$currentDate=date("Ymd");}else{
$currentDate=date("Ymd",strtotime("+$jourprog days"));}
if(strpos($debut,$currentDate)===false){continue;}
$heureDebut=date("Hi",strtotime($debut));
$heureFin=date("Hi",strtotime($fin));
if(($heureDebut>=$choixdebut-360&&$heureDebut&&$duree>=235)||
($heureDebut>=$choixdebut-240&&$heureDebut&&$duree>=175)||
($heureDebut>=$choixdebut-180&&$heureDebut&&$duree>=115)||
($heureDebut>=$choixdebut-120&&$heureDebut&&$duree>=110)||
($heureDebut>=$choixdebut-115&&$heureDebut&&$duree>=105)||
($heureDebut>=$choixdebut-110&&$heureDebut&&$duree>=100)||
($heureDebut>=$choixdebut-105&&$heureDebut&&$duree>=95)||
($heureDebut>=$choixdebut-100&&$heureDebut&&$duree>=90)||
($heureDebut>=$choixdebut-95&&$heureDebut&&$duree>=85)||
($heureDebut>=$choixdebut-90&&$heureDebut&&$duree>=80)||
($heureDebut>=$choixdebut&&$heureDebut&&$duree>=$dureemini)){
$previousFinTime=$finTime;}else{continue;}}else{
if($previousFinTime!==null&&$debutTime<$previousFinTime){continue;}}
$titre=$xpath->query("title",$programme)->item(0);
if(!$titre){continue;}
$titre=$titre->nodeValue;
$image=$xpath->query("icon",$programme)->item(0);
if(!$image){continue;}
$image=$image->getAttribute("src");
$description=$xpath->query("desc",$programme)->item(0);
$description=$description?$description->nodeValue:"Aucune description";
$episodeNode=$programme->getElementsByTagName("episode-num")->item(0);
$episode=null;
if($episodeNode&&preg_match("/^([0-9]+)\.([0-9]+)\.$/",$episodeNode->nodeValue,$matches)){
$episode=str_replace("Episode 0","",str_replace(", épisode 0","",str_replace("Saison 0, é","E","Saison {$matches[1]}, épisode {$matches[2]}")));}
$categories=[];
foreach($xpath->query("category",$programme)as $categoryNode){
$categories[]=$categoryNode->nodeValue;}
$categoriesText=implode(", ",$categories);
$programmesChaine[]=[
"titre"=>$titre,
"description"=>$description,
"image"=>$image,
"debut"=>$debut,
"fin"=>$fin,
"duree"=>$duree,
"categories"=>$categoriesText,
"episode"=>$episode
];
$foundProgrammes=true;
$programmesCount++;
$previousFinTime=$finTime;}catch(Exception $e){continue;}}
if(!empty($programmesChaine)){
$resultat[$chaine->getElementsByTagName('display-name')->item(0)->nodeValue]=$programmesChaine;}
$interval+=$decal;}
while(!$foundProgrammes&&$interval<=1440);}
return $resultat;}
function afficherProgrammeTV($programmes){
foreach($programmes as $chaine=>$programmesChaine){
echo '<div class="tvcontainer"><div class="f20px">📺&nbsp;'.htmlspecialchars($chaine).'</div>';
foreach($programmesChaine as $programme){
echo '<div class="tvprogramme"><div class="tvgrid"><img class="tvimage" src="'.htmlspecialchars($programme['image']).'" alt="'.htmlspecialchars($programme['titre']).'"><span class="tvdescription"><span class="tvdescplus"><div class="tvprogtitre"><strong class="f18px">🎬&nbsp;'.htmlspecialchars($programme['titre']).'</strong></div></span><span class="tvdescplus"><div class="tvproginfos">⏰&nbsp;'.date("H:i",strtotime($programme['debut'])).'&nbsp; ⏱️&nbsp;'.$programme['duree'].'&nbsp;mn&nbsp; '.str_replace("&nbsp;🍿&nbsp;indéterminé", "", str_replace("&nbsp;🍿&nbsp;Aucun genre, Aucun sous-genre","",'&nbsp;🍿&nbsp;'.str_replace("Programme ", "", str_replace("Programme, ", "", htmlspecialchars($programme['categories']))))).'</div></span><span class="tvdescplus">'.str_replace("📜&nbsp;Aucune description","",'📜&nbsp;'.mb_strimwidth(htmlspecialchars($programme['description']),0,304,"...")).'</span>';
if($programme['episode']){echo '<span class="tvdescplus">&nbsp;📺&nbsp;'.$programme['episode'].'</span>';}
echo '</span></div></div>';}
echo '</div>';}};
