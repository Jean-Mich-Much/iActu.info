<?php declare(strict_types=1);date_default_timezone_set('Europe/Paris');$sourceFile='Structure/cache/tv/xmltv_tnt.xml';$outputDir='Structure/cache/tv/';$currentDay=(int)date('d');try{if(!file_exists($sourceFile)){return;}
$dom=new DOMDocument();$dom->preserveWhiteSpace=false;$dom->formatOutput=false;try{
$dom->load($sourceFile);}
catch(Exception $e){return;}
$programmes=$dom->getElementsByTagName('programme');$days=[];foreach($programmes as $programme){
$start=strtotime($programme->getAttribute('start'));$day=(int)date('d',$start);if($day>=$currentDay&&$day<=$currentDay+1){
if(!isset($days[$currentDay])){$days[$currentDay]=new DOMDocument('1.0','UTF-8');
$days[$currentDay]->formatOutput=false;$root=$days[$currentDay]->createElement('tv');foreach($dom->documentElement->childNodes as $node){
if($node->nodeName==='channel'){$root->appendChild($days[$currentDay]->importNode($node,true));}}
$days[$currentDay]->appendChild($root);}
$programmeNode=$days[$currentDay]->importNode($programme,true);$days[$currentDay]->documentElement->appendChild($programmeNode);}
if($day>=$currentDay+1&&$day<=$currentDay+2){$nextDay=$currentDay+1;if(!isset($days[$nextDay])){$days[$nextDay]=new DOMDocument('1.0','UTF-8');$days[$nextDay]->formatOutput=false;$root=$days[$nextDay]->createElement('tv');foreach($dom->documentElement->childNodes as $node){if($node->nodeName==='channel'){$root->appendChild($days[$nextDay]->importNode($node,true));}}
$days[$nextDay]->appendChild($root);}
$programmeNode=$days[$nextDay]->importNode($programme,true);$days[$nextDay]->documentElement->appendChild($programmeNode);}}
foreach($days as $day=>$dayDom){$fileName=$outputDir.'xmltv_'.$day.'.xml';try{ob_start();echo $dayDom->saveXML();$xmlContent=ob_get_clean();$valid=strlen($xmlContent)>=256000&&substr_count($xmlContent,'<programme start=')>=384&&str_ends_with(trim($xmlContent),'</tv>')&&strlen($xmlContent)/1024>=384;if($valid){file_put_contents($fileName,$xmlContent);}}
catch(Exception){continue;}}}catch(Exception){return;};