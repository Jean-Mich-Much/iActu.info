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
try{if($programmesCount>=$maxProgrammes){break;}
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
$currentDate=date("Ymd");
}else{$currentDate=date("Ymd",strtotime("+$jourprog days"));}
if(strpos($debut,$currentDate)===false){continue;}
$heureDebut=date("Hi",strtotime($debut));
$heureFin=date("Hi",strtotime($fin));
if(($heureDebut>=$choixdebut-360&&$heureDebut<=$choixfin&&$duree>=340)||
($heureDebut>=$choixdebut-340&&$heureDebut<=$choixfin&&$duree>=320)||
($heureDebut>=$choixdebut-320&&$heureDebut<=$choixfin&&$duree>=300)||
($heureDebut>=$choixdebut-300&&$heureDebut<=$choixfin&&$duree>=280)||
($heureDebut>=$choixdebut-280&&$heureDebut<=$choixfin&&$duree>=260)||
($heureDebut>=$choixdebut-260&&$heureDebut<=$choixfin&&$duree>=240)||
($heureDebut>=$choixdebut-240&&$heureDebut<=$choixfin&&$duree>=220)||
($heureDebut>=$choixdebut-220&&$heureDebut<=$choixfin&&$duree>=200)||
($heureDebut>=$choixdebut-200&&$heureDebut<=$choixfin&&$duree>=180)||
($heureDebut>=$choixdebut-180&&$heureDebut<=$choixfin&&$duree>=170)||
($heureDebut>=$choixdebut-170&&$heureDebut<=$choixfin&&$duree>=160)||
($heureDebut>=$choixdebut-160&&$heureDebut<=$choixfin&&$duree>=150)||
($heureDebut>=$choixdebut-150&&$heureDebut<=$choixfin&&$duree>=140)||
($heureDebut>=$choixdebut-140&&$heureDebut<=$choixfin&&$duree>=130)||
($heureDebut>=$choixdebut-130&&$heureDebut<=$choixfin&&$duree>=120)||
($heureDebut>=$choixdebut-120&&$heureDebut<=$choixfin&&$duree>=110)||
($heureDebut>=$choixdebut-110&&$heureDebut<=$choixfin&&$duree>=105)||
($heureDebut>=$choixdebut-105&&$heureDebut<=$choixfin&&$duree>=100)||
($heureDebut>=$choixdebut-100&&$heureDebut<=$choixfin&&$duree>=95)||
($heureDebut>=$choixdebut-95&&$heureDebut<=$choixfin&&$duree>=90)||
($heureDebut>=$choixdebut-90&&$heureDebut<=$choixfin&&$duree>=85)||
($heureDebut>=$choixdebut-85&&$heureDebut<=$choixfin&&$duree>=80)||
($heureDebut>=$choixdebut-80&&$heureDebut<=$choixfin&&$duree>=75)||
($heureDebut>=$choixdebut-75&&$heureDebut<=$choixfin&&$duree>=70)||
($heureDebut>=$choixdebut-70&&$heureDebut<=$choixfin&&$duree>=65)||
($heureDebut>=$choixdebut-65&&$heureDebut<=$choixfin&&$duree>=60)||
($heureDebut>=$choixdebut-60&&$heureDebut<=$choixfin&&$duree>=55)||
($heureDebut>=$choixdebut-55&&$heureDebut<=$choixfin&&$duree>=50)||
($heureDebut>=$choixdebut-50&&$heureDebut<=$choixfin&&$duree>=45)||
($heureDebut>=$choixdebut-45&&$heureDebut<=$choixfin&&$duree>=40)||
($heureDebut>=$choixdebut-40&&$heureDebut<=$choixfin&&$duree>=35)||
($heureDebut>=$choixdebut-35&&$heureDebut<=$choixfin&&$duree>=30)||
($heureDebut>=$choixdebut-30&&$heureDebut<=$choixfin&&$duree>=25)||
($heureDebut>=$choixdebut-25&&$heureDebut<=$choixfin&&$duree>=20)||
($heureDebut>=$choixdebut-20&&$heureDebut<=$choixfin&&$duree>=15)||
($heureDebut>=$choixdebut-15&&$heureDebut<=$choixfin&&$duree>=10)||
($heureDebut>=$choixdebut-10&&$heureDebut<=$choixfin&&$duree>=5)||
($heureDebut>=$choixdebut&&$heureDebut<=$choixfin&&$duree>=$dureemini)){
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
$episode=str_replace("Episode 0","",str_replace(", épisode 0","",str_replace("Saison 0, é","E","Saison {$matches[1]}, épisode {$matches[2]}")));
}
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
"episode"=>$episode
];
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
function afficherProgrammeTV($programmes){
foreach($programmes as $chaine=>$programmesChaine){
echo '<div class="tvcontainer"><div class="tvchainetitre">📺&nbsp;'.htmlspecialchars($chaine).'</div>';
foreach($programmesChaine as $programme){
echo '<div class="tvcontainerprog"><div class="tvprog"><span class="tvheure">⏰&nbsp;'.date("H:i",strtotime($programme['debut'])).'</span><span class="tvtitre">🎬&nbsp;'.mb_strimwidth(htmlspecialchars($programme['titre']),0,56,"...").(dateLabel($programme['debut']) !== "Aujourd'hui" ? ' ('.dateLabel($programme['debut']).')' : '').'</span></div>';
echo '</div>';}
echo '</div>';}};
