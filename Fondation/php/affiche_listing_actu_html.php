<?php
function log_affiche($m){
$f='Fondation/logs/affiche_html.log';
if(file_exists($f)&&filesize($f)>512)file_put_contents($f,'');
file_put_contents($f,date('[Y-m-d H:i:s] ').$m."\n",FILE_APPEND);
}

function load_flux($f,$mode){
$base=$mode==='fusion'?'Fondation/cache/rss/fusion/':'Fondation/cache/rss/';
$valid='Fondation/cache/rss/valide/';
$x=$base.$f.'.xml';
$v=$valid.$f.'.xml';

$xml=@simplexml_load_file($x);
if($xml && isset($xml->channel->item)){
$nb = count($xml->channel->item);
if($nb >= 5){
copy($x,$v);
return $xml;
}
}

log_affiche("Flux invalide: $x, fallback utilisé.");
$xml=@simplexml_load_file($v);
return $xml?:false;
}

function news_site_name($url){
$p=@parse_url($url);
if(!$p||empty($p['host']))return'';
$h=mb_strtolower($p['host']);
$h=preg_replace('/^www\d*\./','',$h);
$parts=explode('.',$h);
$n=count($parts);
if($n>=2)$root=$parts[$n-2];
else$root=$h;
return ucfirst($root);
}

function news_title_id(){
$a='';
for($i=0;$i<6;$i++)$a.=chr(mt_rand(97,122));
$n=mt_rand(0,100);
return $a.$n;
}

function news_id(){
$y=date('Y');
$d=date('z');
$a='';
for($i=0;$i<7;$i++)$a.=chr(mt_rand(97,122));
$n=mt_rand(0,1000);
return $y.'-'.$d.'-'.$a.$n;
}

function affiche($f,$mode,$n){
$x=load_flux($f,$mode);
if(!$x)return;
$t=[];$o=[];
foreach($x->channel->item as $i){
$ts=strtotime((string)$i->pubDate);
if(!$ts){log_affiche("Date invalide dans $f");continue;}
$d=date('Y-m-d',$ts);
if($d===date('Y-m-d'))$t[]=$i;else$o[]=$i;
}
$nbt=count($t);
if($nbt>=$n){$t=array_slice($t,0,$n);$o=[];}
else{$t=array_slice($t,0,$nbt);$o=array_slice($o,0,$n-$nbt);}
$nb=count($t);

echo'<article class="news-box">';
echo'<header class="news-header"><div class="news-title">';
if($site!=='')echo'<a class="news-title-link" rel="noopener" target="_blank" id="'.news_title_id().'" href="'.$site.'">🚀&nbsp;'.$x->channel->title.'</a>';
else echo'🚀&nbsp;'.$x->channel->title;
echo'</div>';
echo'<div class="news-count">'.($nb>0?$nb.' news aujourd’hui':'Rien de neuf').'&nbsp;⏰</div></header>';

if($nb>0){
echo'<section class="news-section"><div class="section-title">📅&nbsp;Aujourd’hui</div><ul class="news-list">';
foreach($t as $i){
$ts=strtotime((string)$i->pubDate);
$h=date('H:i',$ts);
echo'<li class="news-item"><a class="n" rel="noopener" target="_blank" id="'.news_id().'" href="'.$i->link.'">'
.htmlspecialchars((string)$i->title,ENT_QUOTES,'UTF-8').'<span class="n_heure_news"> &#149; '.$h.'</span>'.'<span class="n_nom_site"> &#149; '.news_site_name($i->link).'</span></a></li>';
}
echo'</ul></section>';
if(!count($o))echo'<section class="news-section"><div class="section-title">☕️&nbsp;C’est tout pour aujourd’hui !</div></section>';
}

if(!$nb&&count($o))echo'<section class="news-section"><div class="section-title">📅&nbsp;Aujourd’hui : pas de news</div></section>';

if(count($o)){
echo'<section class="news-section"><div class="section-title">📅&nbsp;Jours précédents</div><ul class="news-list">';
foreach($o as $i){
$ts=strtotime((string)$i->pubDate);
$h=date('H:i',$ts);
echo'<li class="news-item"><a class="n" rel="noopener" target="_blank" id="'.news_id().'" href="'.$i->link.'">'
.htmlspecialchars((string)$i->title,ENT_QUOTES,'UTF-8').'<span class="n_nom_site"> &#149; '.news_site_name($i->link).'</span></a></li>';
}
echo'</ul></section>';
}

echo'</article>';
}
