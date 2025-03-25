<?php ob_start();?>

<?php
function wordLimit($text, $maxChars) {if (mb_strlen($text) <= $maxChars) return $text;
$words = explode(' ', $text);$output = '';foreach ($words as $word) {if (mb_strlen($output . ' ' . $word) > $maxChars) break;
$output .= ' ' . $word;}
return trim($output) . '...';}
?>

<?php
function dateLabel($date){$jour=new DateTime($date);
$aujourdhui=(new DateTime())->format('Y-m-d');$demain=(new DateTime('tomorrow'))->format('Y-m-d');
$hier=(new DateTime('yesterday'))->format('Y-m-d');if($jour->format('Y-m-d')===$aujourdhui){return "Aujourd'hui";}
elseif($jour->format('Y-m-d')===$demain){return "Demain";}
elseif($jour->format('Y-m-d')===$hier){return "Hier";}
else{$joursFrancais=['dimanche','lundi','mardi','mercredi','jeudi','vendredi','samedi'];
return $joursFrancais[$jour->format('w')];}}
?>

<?php
function tv($source, $startTime, $minDuration, $dayOffset, $maxProgrammes,$encours, $primetime){$programmes=[];try{if(!file_exists($source)){return $programmes;}
$xml=new SimpleXMLElement(file_get_contents($source));$baseDate=(new DateTime())->modify("+$dayOffset days");
$startTime=$baseDate->setTime((int)substr($startTime,0,2),(int)substr($startTime,2,2));$maxEndDate=(clone $baseDate)->modify('+5 days')->setTime(23,59);
foreach($xml->programme as $programme){try{$debut=new DateTime((string)$programme['start']);
$fin=new DateTime((string)$programme['stop']);$finprimetv = (int)(new DateTime((string)$programme['stop']))->format('Hi');
if ($encours === '1') {$currentDateTime=new DateTime();if($debut<=$currentDateTime&&$fin>$currentDateTime){$startTime=$debut;}}
$currentDay = $baseDate->format('Y-m-d');$debutDay = (new DateTime((string)$programme['start']))->format('Y-m-d');
if(($primetime==='1'&&(int)$debut->format('Hi')>=1149&&$debutDay===$currentDay&&$finprimetv>2129)||
($primetime==='1'&&(int)$debut->format('Hi')>=1249&&$debutDay===$currentDay&&$finprimetv>2129)||
($primetime==='1'&&(int)$debut->format('Hi')>=1349&&$debutDay===$currentDay&&$finprimetv>2129)||
($primetime==='1'&&(int)$debut->format('Hi')>=1449&&$debutDay===$currentDay&&$finprimetv>2129)||
($primetime==='1'&&(int)$debut->format('Hi')>=1549&&$debutDay===$currentDay&&$finprimetv>2129)||
($primetime==='1'&&(int)$debut->format('Hi')>=1649&&$debutDay===$currentDay&&$finprimetv>2129)||
($primetime==='1'&&(int)$debut->format('Hi')>=1749&&$debutDay===$currentDay&&$finprimetv>2129)||
($primetime==='1'&&(int)$debut->format('Hi')>=1849&&$debutDay===$currentDay&&$finprimetv>2129)||
($primetime==='1'&&(int)$debut->format('Hi')>=1949&&$debutDay===$currentDay&&$finprimetv>2129)||
($debut>=$startTime&&$fin<=$maxEndDate))
{$duree=$debut->diff($fin)->i+($debut->diff($fin)->h*60);if($duree>=$minDuration){$channelId=(string)$programme['channel'];
$titre=(string)$programme->title;$description=(string)$programme->desc??'Aucune description disponible';
$categories=[];foreach($programme->category as $category){$categories[]=(string)$category;}
if(!isset($programmes[$channelId])){$programmes[$channelId]=[];}
if(count($programmes[$channelId])<$maxProgrammes){$programmes[$channelId][]=['debut'=>$debut->format('Y-m-d H:i:s'),'fin'=>$fin->format('Y-m-d H:i:s'),'titre'=>$titre,'duree'=>$duree,'categories'=>implode(', ',$categories),'description'=>$description];}}}}catch(Exception $e){continue;}}}catch(Exception $e){return $programmes;}
return $programmes;}
?>

<?php
function afficherProgrammeTVComplet($programmes){foreach($programmes as $chaine=>$programmesChaine){
echo '<div class="tvcontainer"><div class="f20px">📺&nbsp;'.htmlspecialchars(str_replace(
 ['.fr', 'NT1', 'LEquipe21', 'Numero23', 'RMCDecouverte', 'Cherie25', 'ParisPremiere', 'CanalPlusSport', 'CanalPlusCinema', 'PlanetePlus', 'CanalPlus', 'France2', 'France3', 'France5', 'France4', 'LaChaineParlementaire', 'BFMTV', 'TF1SeriesFilms', 'FranceInfo'],
['', 'TFX', 'La chaine l’Équipe', 'RMC STORY', 'RMC Découverte', 'Chérie 25', 'Paris Première', 'Canal+ Sport', 'Canal+ Cinéma', 'Planete+', 'Canal+', 'France 2', 'France 3', 'France 5', 'France 4', 'LCP', 'BFM TV', 'TF1 Séries-Films', 'France Info'],
$chaine)).'</div>';foreach($programmesChaine as $programme){if(!isset($programme['debut'])||!isset($programme['titre'])){continue;}
$debut=new DateTime($programme['debut']);$heureDebut=$debut->format('H:i');$jourLabel=dateLabel($programme['debut']);
$titre=wordLimit(htmlspecialchars($programme['titre']),108);$jourInfo=($jourLabel!=="Aujourd'hui")?" (".strtolower($jourLabel).")":"";
$description=wordLimit(htmlspecialchars($programme['description']),216);
echo '<div class="tvprogramme">
<div class="tvgrid">
<span class="tvdescription">
<span class="tvprogtitreplus">
<div class="tvprogtitre">
<strong class="f18px">🎬&nbsp;'.$titre.'</strong>
</div>
</span>
<span class="tvproginfosplus">
<div class="tvproginfos">
⏰&nbsp;'.$heureDebut.$jourInfo.'&nbsp; ⏱️&nbsp;'.$programme['duree'].'&nbsp;mn&nbsp; '.str_replace("🍿&nbsp;indéterminé","",str_replace("&nbsp;🍿&nbsp;Aucun genre, Aucun sous-genre",'','&nbsp;🍿&nbsp;'.str_replace("Programme ","",str_replace("Programme, ","",htmlspecialchars($programme['categories']))))).'
</div>
</span>
<span class="tvdescplus">'.str_replace("📜&nbsp;Aucune description","",'📜&nbsp;'.$description).'</span>
</span>
</div>
</div>';}
echo '</div>';}}
?>

<?php
function afficherProgrammeTVCompact($programmes){foreach($programmes as $chaine=>$programmesChaine){
echo '<div class="tvcontainer"><div class="tvchainetitre">📺&nbsp;'.htmlspecialchars(str_replace(
['.fr', 'NT1', 'LEquipe21', 'Numero23', 'RMCDecouverte', 'Cherie25', 'ParisPremiere', 'CanalPlusSport', 'CanalPlusCinema', 'PlanetePlus', 'CanalPlus', 'France2', 'France3', 'France5', 'France4', 'LaChaineParlementaire', 'BFMTV', 'TF1SeriesFilms', 'FranceInfo'],
['', 'TFX', 'La chaine l’Équipe', 'RMC STORY', 'RMC Découverte', 'Chérie 25', 'Paris Première', 'Canal+ Sport', 'Canal+ Cinéma', 'Planete+', 'Canal+', 'France 2', 'France 3', 'France 5', 'France 4', 'LCP', 'BFM TV', 'TF1 Séries-Films', 'France Info'],
$chaine)).'</div>';
foreach($programmesChaine as $programme){if(!isset($programme['debut'])||!isset($programme['titre'])){continue;}
$debut=new DateTime($programme['debut']);$heureDebut=$debut->format('H:i');$jourLabel=dateLabel($programme['debut']);
$titre=wordLimit(htmlspecialchars($programme['titre']),54);$jourInfo=($jourLabel!=="Aujourd'hui")?" (".strtolower($jourLabel).")":"";
echo '<div class="tvcontainerprog">
<div class="tvprog">
<span class="tvheure">⏰&nbsp;'.$heureDebut.'</span>
<span class="tvtitre">🎬&nbsp;'.$titre.$jourInfo.'</span>
</div></div>';}
echo '</div>';}}
?>

<?php
function afficherProgrammeTV($programmes, $completOuCompact) {if ($completOuCompact == 0) {afficherProgrammeTVComplet($programmes);} elseif ($completOuCompact == 1) {afficherProgrammeTVCompact($programmes);}};
?>

<?php ob_end_flush();
