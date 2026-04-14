<?php
$rss_last_error=null;

function parse(...$args){
global $rss_last_error;
$argc=count($args);

if($argc===3){
$nom=$args[0];$url=$args[1];$max=(int)$args[2];
$dir=__DIR__.'/../../cache/rss/';
if(!is_dir($dir)){@mkdir($dir,0775,true);@chown($dir,'caddy');@chgrp($dir,'caddy');}
$f=$dir.$nom.'.xml';
$l=$dir.$nom.'.lock';
$idir=__DIR__.'/../../cache/rss/indispo/';
if(!is_dir($idir)){@mkdir($idir,0775,true);@chown($idir,'caddy');@chgrp($idir,'caddy');}
$ilog=$idir.$nom.'.log';
$hasArchive=is_file($f);
$age=$hasArchive?time()-filemtime($f):null;
@file_put_contents($l,'1');@chmod($l,0664);@chown($l,'caddy');@chgrp($l,'caddy');
try{
$ttl=random_int(1920,3840);
if(is_file($f)&&time()-filemtime($f)<$ttl)return;
$rss_last_error=null;
$x=fetch_feed($url,9);
if(!$x){
if($hasArchive&&$age!==null&&$age>=14400)rss_log_indispo($nom,'Indisponibilité flux (archive >=4h) : '.($rss_last_error?:'Erreur inconnue'));
elseif(!$hasArchive)rss_log_indispo($nom,'Indisponibilité flux : '.($rss_last_error?:'Erreur inconnue'));
return;
}
$raw=parse_feed($x);
if(!$raw){
if($hasArchive&&$age!==null&&$age>=14400)rss_log_indispo($nom,'Flux illisible (archive >=4h) : '.($rss_last_error?:'XML invalide'));
elseif(!$hasArchive)rss_log_indispo($nom,'Flux illisible : '.($rss_last_error?:'XML invalide'));
return;
}

$buffer=$raw;
$buffer=sort_items($buffer);
$buffer=normalize_items($buffer,$url);
$buffer=filter_items($buffer);
$buffer=dedupe_items($buffer);
$buffer=sort_items($buffer);
$buffer=array_slice($buffer,0,$max);

if(count($buffer)!==$max)return;
$r=generate_rss($nom,$buffer);if(!$r)return;
if(!validate_buffer($r,count($buffer)))return;
@file_put_contents($f,$r);
@chmod($f,0664);@chown($f,'caddy');@chgrp($f,'caddy');
if(is_file($ilog))@unlink($ilog);
}finally{@unlink($l);}
return;
}

if($argc===7){
$titre=$args[0];$slug=$args[1];$categorie=$args[2];$prefixe=$args[3];$url=$args[4];$min=(int)$args[5];$max=(int)$args[6];
$dir=__DIR__.'/../../cache/rss/';
if(!is_dir($dir)){@mkdir($dir,0775,true);@chown($dir,'caddy');@chgrp($dir,'caddy');}
$base=$dir.$prefixe.'_'.$slug;
$f=$base.'.xml';
$l=$base.'.lock';
$tmp=$base.'.tmp';
$ready=$base.'.ready';
$log=$base.'.log';
$idir=__DIR__.'/../../cache/rss/indispo/';
if(!is_dir($idir)){@mkdir($idir,0775,true);@chown($idir,'caddy');@chgrp($idir,'caddy');}
$ilog=$idir.$prefixe.'_'.$slug.'.log';
if(is_file($tmp))@unlink($tmp);
if(is_file($log)){
$sz=filesize($log);
$mt=filemtime($log)?:time();
if($sz>512||time()-$mt>604800)@file_put_contents($log,'');
}
@file_put_contents($l,'1');@chmod($l,0664);@chown($l,'caddy');@chgrp($l,'caddy');
try{
$ttl=random_int(1800,3600);
$hasArchive=is_file($f);
$age=$hasArchive?time()-filemtime($f):null;
if(is_file($f)&&time()-filemtime($f)<$ttl){
if(!is_file($ready)){@touch($ready);@chmod($ready,0664);@chown($ready,'caddy');@chgrp($ready,'caddy');}
return;
}
$t0=microtime(true);
$rss_last_error=null;
$x=fetch_feed($url,8);
$dt=microtime(true)-$t0;
if($dt>3.0&&$hasArchive&&!is_file($ready)){
@touch($ready);@chmod($ready,0664);@chown($ready,'caddy');@chgrp($ready,'caddy');
}

if(!$x){
if($hasArchive&&!is_file($ready)){
@touch($ready);@chmod($ready,0664);@chown($ready,'caddy');@chgrp($ready,'caddy');
}
if($age!==null&&$age>=14400)rss_log_indispo($prefixe.'_'.$slug,'Indisponibilité flux (archive >=4h) : '.($rss_last_error?:'Erreur inconnue'));
else rss_log_indispo($prefixe.'_'.$slug,'Indisponibilité flux : '.($rss_last_error?:'Erreur inconnue'));

$buffer=rss_load_daily($dir,$prefixe,$slug);
if($buffer){
$buffer=sort_items($buffer);
$buffer=normalize_items($buffer,$url);
$buffer=filter_items($buffer);
$buffer=dedupe_items($buffer);
$buffer=sort_items($buffer);
$buffer=array_slice($buffer,0,$max);
if(count($buffer)>=5){
$r=generate_rss($titre,$buffer,$categorie,$prefixe);if(!$r)return;
if(!validate_buffer($r,count($buffer)))return;
@file_put_contents($tmp,$r);
@chmod($tmp,0664);@chown($tmp,'caddy');@chgrp($tmp,'caddy');
@rename($tmp,$f);
@touch($ready);@chmod($ready,0664);@chown($ready,'caddy');@chgrp($ready,'caddy');
return;
}
}
return;
}

$raw=parse_feed($x);
if(!$raw){
if($hasArchive&&!is_file($ready)){
@touch($ready);@chmod($ready,0664);@chown($ready,'caddy');@chgrp($ready,'caddy');
}
if($age!==null&&$age>=14400)rss_log_indispo($prefixe.'_'.$slug,'Flux illisible (archive >=4h) : '.($rss_last_error?:'XML invalide'));
else rss_log_indispo($prefixe.'_'.$slug,'Flux illisible : '.($rss_last_error?:'XML invalide'));

$buffer=rss_load_daily($dir,$prefixe,$slug);
if($buffer){
$buffer=sort_items($buffer);
$buffer=normalize_items($buffer,$url);
$buffer=filter_items($buffer);
$buffer=dedupe_items($buffer);
$buffer=sort_items($buffer);
$buffer=array_slice($buffer,0,$max);
if(count($buffer)>=5){
$r=generate_rss($titre,$buffer,$categorie,$prefixe);if(!$r)return;
if(!validate_buffer($r,count($buffer)))return;
@file_put_contents($tmp,$r);
@chmod($tmp,0664);@chown($tmp,'caddy');@chgrp($tmp,'caddy');
@rename($tmp,$f);
@touch($ready);@chmod($ready,0664);@chown($ready,'caddy');@chgrp($ready,'caddy');
return;
}
}
return;
}

$raw_today=$raw;

rss_save_daily($dir,$prefixe,$slug,$titre,$categorie,$raw_today);
rss_cleanup_daily($dir,$prefixe,$slug);

$buffer=$raw_today;

if(count($buffer)<$max){
$archives=rss_load_daily($dir,$prefixe,$slug);
if($archives)$buffer=array_merge($buffer,$archives);
}

if(!$buffer){
if($hasArchive&&!is_file($ready)){
@touch($ready);@chmod($ready,0664);@chown($ready,'caddy');@chgrp($ready,'caddy');
}
if($age!==null&&$age>=14400)rss_log_indispo($prefixe.'_'.$slug,'Moins de 5 news disponibles (archive >=4h)');
else rss_log_indispo($prefixe.'_'.$slug,'Moins de 5 news disponibles');
return;
}

$buffer=sort_items($buffer);
$buffer=normalize_items($buffer,$url);
$buffer=filter_items($buffer);
$buffer=dedupe_items($buffer);
$buffer=sort_items($buffer);
$buffer=array_slice($buffer,0,$max);

if(count($buffer)<5){
if($hasArchive&&!is_file($ready)){
@touch($ready);@chmod($ready,0664);@chown($ready,'caddy');@chgrp($ready,'caddy');
}
if($age!==null&&$age>=14400)rss_log_indispo($prefixe.'_'.$slug,'Moins de 5 news disponibles (archive >=4h)');
else rss_log_indispo($prefixe.'_'.$slug,'Moins de 5 news disponibles');
return;
}

$r=generate_rss($titre,$buffer,$categorie,$prefixe);if(!$r)return;
if(!validate_buffer($r,count($buffer)))return;
@file_put_contents($tmp,$r);
@chmod($tmp,0664);@chown($tmp,'caddy');@chgrp($tmp,'caddy');
@rename($tmp,$f);
@touch($ready);@chmod($ready,0664);@chown($ready,'caddy');@chgrp($ready,'caddy');
if(is_file($ilog))@unlink($ilog);
}finally{
@unlink($l);
if(is_file($tmp))@unlink($tmp);
}
if($log){
@file_put_contents($log,'['.date('Y-m-d H:i:s')."] OK $prefixe/$slug items:".($max)." min:$min\n",FILE_APPEND);
}
}
}

function validate_buffer($r,$max){
global $rss_last_error;
$r=trim($r);
if($r===''){ $rss_last_error='Buffer vide';return false;}
if(substr_count($r,'<item>')!==$max){$rss_last_error='Nombre d’items invalide';return false;}
if(substr_count($r,'<title>')<$max){$rss_last_error='Titres manquants';return false;}
if(substr_count($r,'<link>https')<$max&&substr_count($r,'<link>http')<$max){$rss_last_error='Liens manquants';return false;}
if(substr_count($r,'<pubDate>')<$max){$rss_last_error='Dates manquantes';return false;}
if(substr_count($r,'<channel>')<1){$rss_last_error='Channel manquant';return false;}
if(substr_count($r,'</channel>')<1){$rss_last_error='Channel de fin manquant';return false;}
if(substr_count($r,'<rss')<1){$rss_last_error='RSS manquant';return false;}
if(substr_count($r,'</rss>')<1){$rss_last_error='RSS de fin manquant';return false;}
if(strpos($r,'<?xml')!==0){$rss_last_error='En-tête XML manquant';return false;}
$end=substr($r,-32);
if(strpos($end,'</rss>')===false){$rss_last_error='Fin RSS invalide';return false;}
libxml_use_internal_errors(true);
if(!simplexml_load_string($r)){$rss_last_error='XML invalide';return false;}
return true;
}

function fetch_feed($u,$timeout=5){
global $rss_last_error;
$u=force_https($u);
$ua='Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:148.0) Gecko/20100101 Firefox/148.0';
$headers=[
'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
'Accept-Language: fr-FR,fr;q=0.8,en-US;q=0.5,en;q=0.3',
'Accept-Encoding: gzip, deflate, br',
'Connection: keep-alive',
'DNT: 1',
'Upgrade-Insecure-Requests: 1',
'Referer: https://www.google.fr/'
];

usleep(random_int(25000,125000));

$ch=curl_init();
curl_setopt_array($ch,[
CURLOPT_URL=>$u,
CURLOPT_RETURNTRANSFER=>true,
CURLOPT_FOLLOWLOCATION=>true,
CURLOPT_MAXREDIRS=>5,
CURLOPT_TIMEOUT=>$timeout,
CURLOPT_USERAGENT=>$ua,
CURLOPT_HTTPHEADER=>$headers,
CURLOPT_ENCODING=>'',
CURLOPT_SSL_VERIFYPEER=>false,
CURLOPT_SSL_VERIFYHOST=>0,
CURLOPT_COOKIEFILE=>'',
CURLOPT_COOKIEJAR=>'',
CURLOPT_MAXFILESIZE=>3000000
]);

$d=curl_exec($ch);
$info=curl_getinfo($ch);
if(curl_errno($ch)){
$rss_last_error='Erreur cURL '.curl_errno($ch).' : '.curl_error($ch);
$d=false;
}else{
$code=$info['http_code']??0;
if($code>=400){$rss_last_error='Erreur HTTP '.$code;$d=false;}
}
curl_close($ch);
if(!$d)return null;
if(strlen($d)>3000000){$rss_last_error='Flux trop volumineux (>3 Mo)';return null;}
if($d===''){ $rss_last_error='Flux vide';return null;}
return fix_utf8($d);
}

function clean_xml($x){return preg_replace('/[^\P{C}\n\t]/u','',$x);}

function parse_feed($x){
global $rss_last_error;
libxml_use_internal_errors(true);
$x=clean_xml($x);
$f=simplexml_load_string($x);
if(!$f){$rss_last_error='Flux illisible (XML invalide)';return null;}
$it=[];
if(isset($f->channel->item))foreach($f->channel->item as $a)$it[]=['title'=>trim((string)$a->title),'link'=>trim((string)$a->link),'desc'=>trim((string)$a->description),'date'=>normalize_date($a)];
if(isset($f->entry))foreach($f->entry as $a)$it[]=['title'=>trim((string)$a->title),'link'=>trim((string)$a->link['href']),'desc'=>trim((string)$a->summary),'date'=>normalize_date($a)];
return $it;
}

function normalize_date($a){
$k=['pubDate','published','updated','date'];
foreach($k as $f)if(isset($a->$f)){ $d=try_parse_date((string)$a->$f);if($d!==false)return $d;}
return time();
}

function try_parse_date($s){
$s=trim($s);if($s==='')return false;
$t=strtotime($s);if($t!==false)return fix_future_date($t);
$f=['Y-m-d H:i:s','d/m/Y H:i','d-m-Y','m/d/Y','D, d M Y H:i:s O','Y-m-d\TH:i:sP','Y-m-d\TH:i:sZ','Y-m-d\TH:i:s.uP'];
foreach($f as $p){$dt=DateTime::createFromFormat($p,$s);if($dt)return fix_future_date($dt->getTimestamp());}
return false;
}

function fix_future_date($t){return $t>time()?time():$t;}

function normalize_items($it,$b){
foreach($it as &$a){
$a['title']=normalize_title($a['title'],$a['desc']);
$a['link']=normalize_url($a['link'],$b);
if($a['title']===''||$a['link']===''||!filter_var($a['link'],FILTER_VALIDATE_URL))$a=null;
}
return array_values(array_filter($it));
}

function normalize_title($s,$d){
$s=fix_utf8($s);$s=fix_entities($s);$s=strip_tags($s);
$s=str_replace(["\t","\r","\n"],' ',$s);$s=preg_replace('/\s+/',' ',$s);$s=trim($s);
if($s===''||$s==='.'||$s==='...'||mb_strlen($s)<3){
$s=strip_tags($d);$s=fix_entities($s);$s=preg_replace('/\s+/',' ',$s);$s=trim($s);
$s=implode(' ',array_slice(explode(' ',$s),0,12));
}
$s=title_sentence_case($s);
if(mb_strlen($s)>256)$s=mb_substr($s,0,256).'...';
return $s;
}

function title_sentence_case($s){
$s=preg_replace('/\s+/',' ',$s);
$c=preg_split('//u',$s,-1,PREG_SPLIT_NO_EMPTY);
$i=0;while($i<count($c)&&!preg_match('/\p{L}/u',$c[$i]))$i++;
if($i<count($c))$c[$i]=mb_strtoupper($c[$i]);
return implode('',$c);
}

function normalize_url($l,$b){
$l=trim($l);if($l==='')return $l;
if(strpos($l,'http')!==0){
$p=parse_url($b);$s=$p['scheme']??'https';$h=$p['host']??'';
if(strpos($l,'//')===0)return $s.':'.$l;
if(strpos($l,'/')===0)return $s.'://'.$h.$l;
if($h)$l=$s.'://'.$h.'/'.ltrim($l,'/');
}
$l=str_replace(['https//','http//'],['https://','http://'],$l);
$l=preg_replace('#^https:/([^/])#','https://$1',$l);
$l=preg_replace('#^http:/([^/])#','http://$1',$l);
return force_https($l);
}

function fix_entities($s){
$s=str_replace('&amp;#','&#',$s);
$s=preg_replace('/&#?(\d{2,})(;?)/','&#$1;',$s);
$s=preg_replace('/&#x([0-9a-fA-F]+)(;?)/','&#x$1;',$s);
return html_entity_decode($s,ENT_QUOTES|ENT_HTML5,'UTF-8');
}

function filter_items($it){
$r=[];foreach($it as $a){if(strlen($a['title'])<5)continue;if(strlen($a['link'])<5)continue;$r[]=$a;}return $r;
}

function dedupe_items($it){
$f=[];foreach($it as $a){$x=false;foreach($f as $k=>$b){if($a['link']===$b['link']||similar_titles($a['title'],$b['title'])){$x=true;if($a['date']>$b['date'])$f[$k]=$a;break;}}if(!$x)$f[]=$a;}return $f;
}

function similar_titles($a,$b){
$a=normalize_title_compare($a);$b=normalize_title_compare($b);
$wa=preg_split('/\W+/u',$a);$wb=preg_split('/\W+/u',$b);
$wa=array_filter($wa,fn($w)=>mb_strlen($w)>=5);
$wb=array_filter($wb,fn($w)=>mb_strlen($w)>=5);
return count(array_intersect($wa,$wb))>=3;
}

function normalize_title_compare($s){
$s=mb_strtolower($s);
$s=preg_replace('/[\[\]\(\){}]/',' ',$s);
$s=preg_replace('/[^\p{L}\p{N}\s]/u',' ',$s);
$s=preg_replace('/\s+/',' ',$s);
return trim($s);
}

function sort_items($it){usort($it,fn($a,$b)=>$b['date']<=>$a['date']);return $it;}

function generate_rss($n,$it,$categorie='',$prefixe=''){
$r="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<rss version=\"2.0\">\n<channel>\n<title>".htmlspecialchars($n,ENT_XML1)."</title>\n";
if($categorie!=='')$r.="<category>".htmlspecialchars($categorie,ENT_XML1)."</category>\n";
foreach($it as $a)$r.="<item>\n<title>".htmlspecialchars($a['title'],ENT_XML1)."</title>\n<link>".htmlspecialchars($a['link'],ENT_XML1)."</link>\n<pubDate>".date(DATE_RSS,$a['date'])."</pubDate>\n</item>\n";
return $r."</channel>\n</rss>\n";
}

function fix_utf8($s){
$s=@mb_convert_encoding($s,'UTF-8','UTF-8');
$s=@iconv('UTF-8','UTF-8//IGNORE',$s);
$s=str_replace("\r\n","\n",$s);
return preg_replace('/[^\P{C}\n\t]/u','',$s);
}

function force_https($u){return preg_replace('#^http://#i','https://',$u);}

function rss_log_indispo($id,$msg){
$dir=__DIR__.'/../../cache/rss/indispo/';
if(!is_dir($dir)){@mkdir($dir,0775,true);@chown($dir,'caddy');@chgrp($dir,'caddy');}
$f=$dir.$id.'.log';
if(is_file($f)&&filesize($f)>512)@file_put_contents($f,'');
@file_put_contents($f,'['.date('Y-m-d H:i:s').'] '.$msg."\n",FILE_APPEND);
@chmod($f,0664);@chown($f,'caddy');@chgrp($f,'caddy');
}

function rss_save_daily($dir,$prefixe,$slug,$titre,$categorie,$it){
if(!$it)return;
$f=$dir.$prefixe.'_'.$slug.'_'.date('d').'.xml';
$r=generate_rss($titre,$it,$categorie,$prefixe);if(!$r)return;
@file_put_contents($f,$r);
@chmod($f,0664);@chown($f,'caddy');@chgrp($f,'caddy');
}

function rss_cleanup_daily($dir,$prefixe,$slug){
$g=glob($dir.$prefixe.'_'.$slug.'_'."*.xml");
if(!$g)return;
usort($g,fn($a,$b)=>filemtime($b)<=>filemtime($a));
$i=0;foreach($g as $f){$i++;if($i>31)@unlink($f);}
}

function rss_load_daily($dir,$prefixe,$slug){
$g=glob($dir.$prefixe.'_'.$slug.'_'."*.xml");
if(!$g)return [];
usort($g,fn($a,$b)=>filemtime($b)<=>filemtime($a));
$it=[];$i=0;
foreach($g as $f){
$i++;if($i>31)break;
$x=@file_get_contents($f);if(!$x)continue;
$p=parse_feed($x);if(!$p)continue;
foreach($p as $a)$it[]=$a;
}
return $it;
}
