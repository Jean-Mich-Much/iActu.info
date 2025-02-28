<?php
function tv($fichier, $choixdebut, $choixfin, $dureemini, $jourprog, $avantapres, $decal, $maxProgrammes){
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
if(($heureDebut>=$choixdebut-1085&&$heureDebut<=$choixfin&&$duree>=$dureemini+1075)||
($heureDebut>=$choixdebut-1025&&$heureDebut<=$choixfin&&$duree>=$dureemini+1015)||
($heureDebut>=$choixdebut-965&&$heureDebut<=$choixfin&&$duree>=$dureemini+955)||
($heureDebut>=$choixdebut-905&&$heureDebut<=$choixfin&&$duree>=$dureemini+895)||
($heureDebut>=$choixdebut-845&&$heureDebut<=$choixfin&&$duree>=$dureemini+835)||
($heureDebut>=$choixdebut-785&&$heureDebut<=$choixfin&&$duree>=$dureemini+775)||
($heureDebut>=$choixdebut-725&&$heureDebut<=$choixfin&&$duree>=$dureemini+715)||
($heureDebut>=$choixdebut-665&&$heureDebut<=$choixfin&&$duree>=$dureemini+655)||
($heureDebut>=$choixdebut-605&&$heureDebut<=$choixfin&&$duree>=$dureemini+595)||
($heureDebut>=$choixdebut-545&&$heureDebut<=$choixfin&&$duree>=$dureemini+535)||
($heureDebut>=$choixdebut-485&&$heureDebut<=$choixfin&&$duree>=$dureemini+475)||
($heureDebut>=$choixdebut-425&&$heureDebut<=$choixfin&&$duree>=$dureemini+415)||
($heureDebut>=$choixdebut-365&&$heureDebut<=$choixfin&&$duree>=$dureemini+355)||
($heureDebut>=$choixdebut-305&&$heureDebut<=$choixfin&&$duree>=$dureemini+295)||
($heureDebut>=$choixdebut-245&&$heureDebut<=$choixfin&&$duree>=$dureemini+235)||
($heureDebut>=$choixdebut-185&&$heureDebut<=$choixfin&&$duree>=$dureemini+175)||
($heureDebut>=$choixdebut-125&&$heureDebut<=$choixfin&&$duree>=$dureemini+115)||
($heureDebut>=$choixdebut-120&&$heureDebut<=$choixfin&&$duree>=$dureemini+110)||
($heureDebut>=$choixdebut-115&&$heureDebut<=$choixfin&&$duree>=$dureemini+105)||
($heureDebut>=$choixdebut-110&&$heureDebut<=$choixfin&&$duree>=$dureemini+100)||
($heureDebut>=$choixdebut-105&&$heureDebut<=$choixfin&&$duree>=$dureemini+95)||
($heureDebut>=$choixdebut-100&&$heureDebut<=$choixfin&&$duree>=$dureemini+90)||
($heureDebut>=$choixdebut-95&&$heureDebut<=$choixfin&&$duree>=$dureemini+85)||
($heureDebut>=$choixdebut-90&&$heureDebut<=$choixfin&&$duree>=$dureemini+80)||
($heureDebut>=$choixdebut-85&&$heureDebut<=$choixfin&&$duree>=$dureemini+75)||
($heureDebut>=$choixdebut-80&&$heureDebut<=$choixfin&&$duree>=$dureemini+70)||
($heureDebut>=$choixdebut-75&&$heureDebut<=$choixfin&&$duree>=$dureemini+65)||
($heureDebut>=$choixdebut-70&&$heureDebut<=$choixfin&&$duree>=$dureemini+60)||
($heureDebut>=$choixdebut-65&&$heureDebut<=$choixfin&&$duree>=$dureemini+55)||
($heureDebut>=$choixdebut-60&&$heureDebut<=$choixfin&&$duree>=$dureemini+50)||
($heureDebut>=$choixdebut-55&&$heureDebut<=$choixfin&&$duree>=$dureemini+45)||
($heureDebut>=$choixdebut-50&&$heureDebut<=$choixfin&&$duree>=$dureemini+40)||
($heureDebut>=$choixdebut-45&&$heureDebut<=$choixfin&&$duree>=$dureemini+35)||
($heureDebut>=$choixdebut-40&&$heureDebut<=$choixfin&&$duree>=$dureemini+30)||
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
function afficherProgrammeTV($programmes){
foreach($programmes as $chaine=>$programmesChaine){
echo '<div class="tvcontainer"><div class="f20px">📺&nbsp;'.htmlspecialchars($chaine).'</div>';
foreach($programmesChaine as $programme){
echo '<div class="tvprogramme"><div class="tvgrid"><img class="tvimage" src="'.htmlspecialchars($programme['image']).'" alt="'.htmlspecialchars($programme['titre']).'"><span class="tvdescription"><span class="tvdescplus"><div class="tvprogtitre"><strong class="f18px">🎬&nbsp;'.htmlspecialchars($programme['titre']).'</strong></div></span><span class="tvdescplus"><div class="tvproginfos">⏰&nbsp;'.date("H:i",strtotime($programme['debut'])).'&nbsp; ⏱️&nbsp;'.$programme['duree'].'&nbsp;mn&nbsp; '.str_replace("&nbsp;🍿&nbsp;indéterminé", "", str_replace("&nbsp;🍿&nbsp;Aucun genre, Aucun sous-genre","",'&nbsp;🍿&nbsp;'.str_replace("Programme ", "", str_replace("Programme, ", "", htmlspecialchars($programme['categories']))))).'</div></span><span class="tvdescplus">'.str_replace("📜&nbsp;Aucune description","",'📜&nbsp;'.mb_strimwidth(htmlspecialchars($programme['description']),0,304,"...")).'</span>';
// if($programme['director']) { echo '<span class="tvdescplus">&nbsp;🎬&nbsp;Réalisé par&nbsp;'.$programme['director'].'</span>'; }
// if(!empty($programme['actors'])) { echo '<span class="tvdescplus">&nbsp;🎭&nbsp;Acteurs:&nbsp;'.implode(", ",$programme['actors']).'</span>'; }
// if(!empty($programme['guests'])) { echo '<span class="tvdescplus">&nbsp;👤&nbsp;Production&nbsp;&#47;&nbsp;invité&nbsp;&#47;&nbsp;autre&nbsp;:&nbsp;'.implode(", ",$programme['guests']).'</span>'; }
// if($programme['rating']) { echo '<span class="tvdescplus">&nbsp;👀&nbsp;Contenu&nbsp;:&nbsp;'.str_replace("Tout public ans", "tout public", str_replace("-Tout public", "Tout public", $programme['rating']).' ans').'</span>'; }
if($programme['episode']){echo '<span class="tvdescplus">&nbsp;📺&nbsp;'.$programme['episode'].'</span>';}
echo '</span></div></div>';
}
echo '</div>';
}};
