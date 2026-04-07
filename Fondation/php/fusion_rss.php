<?php
declare(strict_types=1);

function fusion_rss(string $title,string $prefix,int $min,int $max):void{
$base=__DIR__.'/../cache/rss/';
$dir=$base.'fusion/';
$logDir=__DIR__.'/../logs/';
$logFile=$logDir.'fusion.log';
if(!is_dir($dir)){@mkdir($dir,0775,true);@chmod($dir,0775);@chown($dir,'caddy');@chgrp($dir,'caddy');}
if(!is_dir($logDir)){@mkdir($logDir,0775,true);@chmod($logDir,0775);}
if(!is_file($logFile)){@touch($logFile);@chmod($logFile,0664);}

$files=glob($base.$prefix.'_*.xml')?:[];
if(!$files){fusion_log($logFile,"NO XML prefix:$prefix");return;}

$buffer=[];
foreach($files as$p){
$b=@file_get_contents($p);
if(!$b||!fusion_valid_src($b))continue;
$x=@simplexml_load_string($b);
if(!$x||!isset($x->channel->item))continue;
foreach($x->channel->item as$a){
$t=trim((string)$a->title);
$l=trim((string)$a->link);
$d=strtotime((string)$a->pubDate);
if(!$t||!$l||!$d||mb_strlen($t)<3)continue;
$buffer[]=['title'=>$t,'link'=>$l,'date'=>$d];
}
}

if(!$buffer){fusion_log($logFile,"NO ITEMS prefix:$prefix");return;}

usort($buffer,fn($a,$b)=>$b['date']<=>$a['date']);

$items=fusion_dedupe($buffer);
$items=array_slice($items,0,$max);

if(count($items)<$min){
fusion_log($logFile,"NOT ENOUGH ITEMS prefix:$prefix count:".count($items)." min:$min");
return;
}

$r=fusion_generate_rss($title,$items);
if(!fusion_valid_final($r,$min)){
fusion_log($logFile,"INVALID FINAL prefix:$prefix");
return;
}

$tmp=$dir.$prefix.'.xml.tmp';
$f=$dir.$prefix.'.xml';
@file_put_contents($tmp,$r);
@chmod($tmp,0664);@chown($tmp,'caddy');@chgrp($tmp,'caddy');
@rename($tmp,$f);
fusion_log($logFile,"OK prefix:$prefix items:".count($items));
}

function fusion_log(string$f,string$m):void{
if(is_file($f)){
$sz=filesize($f);
$mt=filemtime($f)?:time();
if($sz>512||time()-$mt>604800)@file_put_contents($f,'');
}
@file_put_contents($f,'['.date('Y-m-d H:i:s')."] $m\n",FILE_APPEND);
}

function fusion_valid_src(string$b):bool{
return strpos($b,'<item>')!==false;
}

function fusion_valid_final(string$b,int$min):bool{
return substr_count($b,'<item>')>=$min;
}

function fusion_dedupe(array$it):array{
$f=[];
$norm=[];
$words=[];
foreach($it as$a){
$t=$a['title'];
if(!isset($norm[$t])){
$n=fusion_norm_title($t);
$w=preg_split('/\W+/u',$n);
$w=array_filter($w,fn($x)=>mb_strlen($x)>=5);
$norm[$t]=$n;
$words[$t]=$w;
}
$dup=false;
foreach($f as$k=>$b){
if($a['link']===$b['link']){
$dup=true;
if($a['date']>$b['date'])$f[$k]=$a;
break;
}
if(fusion_similar_titles_fast($words[$t],$words[$b['title']])){
$dup=true;
if($a['date']>$b['date'])$f[$k]=$a;
break;
}
}
if(!$dup)$f[]=$a;
}
return$f;
}

function fusion_similar_titles_fast(array$wa,array$wb):bool{
if(!$wa||!$wb)return false;
return count(array_intersect($wa,$wb))>=4;
}

function fusion_similar_titles(string$a,string$b):bool{
$a=fusion_norm_title($a);
$b=fusion_norm_title($b);
$wa=preg_split('/\W+/u',$a);
$wb=preg_split('/\W+/u',$b);
$wa=array_filter($wa,fn($w)=>mb_strlen($w)>=5);
$wb=array_filter($wb,fn($w)=>mb_strlen($w)>=5);
return count(array_intersect($wa,$wb))>=4;
}

function fusion_norm_title(string$s):string{
$s=mb_strtolower($s);
$s=preg_replace('/[^\p{L}\p{N}\s]/u',' ',$s);
$s=preg_replace('/\s+/',' ',$s);
return trim($s);
}

function fusion_generate_rss(string$n,array$it):string{
$r="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<rss version=\"2.0\">\n<channel>\n<title>".htmlspecialchars($n,ENT_XML1)."</title>\n";
foreach($it as$a){
$r.="<item>\n<title>".htmlspecialchars($a['title'],ENT_XML1)."</title>\n<link>".htmlspecialchars($a['link'],ENT_XML1)."</link>\n<pubDate>".date(DATE_RSS,$a['date'])."</pubDate>\n</item>\n";
}
return$r."</channel>\n</rss>\n";
}
