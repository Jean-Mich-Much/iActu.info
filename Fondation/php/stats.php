<?php
declare(strict_types=1);
$stats_dir=__DIR__.'/../stats';
$lock_file=$stats_dir.'/lock';
$log_file=$stats_dir.'/log.txt';
$ip_limit_bytes=2097152;
$log_limit_bytes=32768;
$retention_days=31;
$secret_key='GonKirua';

function stats_log(string $file,string $msg):void{
$now=(new DateTimeImmutable('now'))->format(DateTimeInterface::ATOM);
$line=$now.' '.$msg."\n";
if(!is_dir(dirname($file))){return;}
if(file_exists($file)){
$age=time()-filemtime($file);
$size=filesize($file);
if($age>86400*31||$size===false||$size>$GLOBALS['log_limit_bytes']){
@file_put_contents($file,'');
}
}
@file_put_contents($file,$line,FILE_APPEND);
}

function stats_ensure_dir(string $dir,string $log_file):bool{
if(is_dir($dir)){return true;}
if(@mkdir($dir,0775,true)){
@chmod($dir,0775);
return true;
}
stats_log($log_file,'ERR mkdir '.$dir);
return false;
}

function stats_anonymize_ip(string $ip,string $ua,string $day,string $secret):string{
return hash('sha256',$ip.'|'.$ua.'|'.$day.'|'.$secret);
}

function stats_list_files_for_prefix(string $prefix):array{
$files=[];
$base=$prefix.'.txt';
if(file_exists($base)){$files[]=$base;}
$pattern=$prefix.'_split_';
$dir=dirname($base);
if(is_dir($dir)){
$dh=@opendir($dir);
if($dh){
while(($f=readdir($dh))!==false){
if($f==='.'||$f==='..'){continue;}
$path=$dir.'/'.$f;
if(!is_file($path)){continue;}
if(str_starts_with($f,basename($pattern))){
$files[]=$path;
}
}
closedir($dh);
}
}
natsort($files);
return array_values($files);
}

function stats_find_last_file(string $prefix):string{
$files=stats_list_files_for_prefix($prefix);
if(empty($files)){return $prefix.'.txt';}
return $files[array_key_last($files)];
}

function stats_parse_header(string $line):array{
$line=trim($line);
if($line===''||$line[0]!=='{'){return [0,'none'];}
if(preg_match('~^\{"(\d+)","([^"]*)"\}$~',$line,$m)!==1){return [0,'none'];}
return [(int)$m[1],$m[2]];
}

function stats_write_header(string $file,int $count,string $state):bool{
$lines=@file($file,FILE_IGNORE_NEW_LINES);
if($lines===false){return false;}
if(!empty($lines)&&str_starts_with($lines[0],'{')){
$lines[0]='{"'.$count.'","'.$state.'"}';
}else{
array_unshift($lines,'{"'.$count.'","'.$state.'"}');
}
$tmp=$file.'.tmp';
if(@file_put_contents($tmp,implode("\n",$lines)."\n")===false){return false;}
if(!@rename($tmp,$file)){@unlink($tmp);return false;}
@chmod($file,0664);
return true;
}

function stats_count_unique_in_file(string $file,bool $skip_header=true):int{
$h=@fopen($file,'rb');
if(!$h){return 0;}
$set=[];
while(($line=fgets($h))!==false){
$line=trim($line);
if($line===''){continue;}
if($skip_header&&$line[0]==='{'){continue;}
$set[$line]=true;
}
fclose($h);
return count($set);
}

function stats_ip_exists_in_file(string $file,string $ip):bool{
$h=@fopen($file,'rb');
if(!$h){return false;}
while(($line=fgets($h))!==false){
$line=trim($line);
if($line===''||$line[0]==='{'){continue;}
if($line===$ip){fclose($h);return true;}
}
fclose($h);
return false;
}

function stats_ip_exists_for_prefix(string $prefix,string $ip):bool{
foreach(stats_list_files_for_prefix($prefix) as $file){
if(stats_ip_exists_in_file($file,$ip)){return true;}
}
return false;
}

function stats_index_path(string $prefix):string{
return $prefix.'.idx';
}

function stats_ip_in_index(string $prefix,string $ip):bool{
$idx=stats_index_path($prefix);
if(!file_exists($idx)){return false;}
$h=@fopen($idx,'rb');
if(!$h){return false;}
while(($line=fgets($h))!==false){
if(trim($line)===$ip){fclose($h);return true;}
}
fclose($h);
return false;
}

function stats_index_add(string $prefix,string $ip,string $log_file):void{
$idx=stats_index_path($prefix);
$exists=file_exists($idx);
$h=@fopen($idx,'ab');
if(!$h){stats_log($log_file,'ERR fopen idx '.$idx);return;}
if(!$exists){@chmod($idx,0664);}
if(@fwrite($h,$ip."\n")===false){stats_log($log_file,'ERR fwrite idx '.$idx);}
@fflush($h);
if(function_exists('fsync')){@fsync($h);}
fclose($h);
}

function stats_split_if_needed(string $prefix,string $log_file,int $limit):void{
$last=stats_find_last_file($prefix);
if(!file_exists($last)){return;}
$size=filesize($last);
if($size===false||$size<=$limit){return;}
$h=@fopen($last,'rb');
$prev_header_count=0;
if($h){
$line=fgets($h);
if($line!==false){[$prev_header_count]=stats_parse_header($line);}
fclose($h);
}
$unique=stats_count_unique_in_file($last,true);
$total=$prev_header_count+$unique;
stats_write_header($last,$total,'end');
$files=stats_list_files_for_prefix($prefix);
$index=0;
foreach($files as $f){
if(preg_match('~_split_(\d+)\.txt$~',$f,$m)){
$idx=(int)$m[1];
if($idx>$index){$index=$idx;}
}
}
$index++;
$new=$prefix.'_split_'.$index.'.txt';
$header='{"'.$total.'","split"}'."\n";
@file_put_contents($new,$header);
@chmod($new,0664);
}

function stats_update_dynamic_header(string $prefix):void{
$last=stats_find_last_file($prefix);
if(!file_exists($last)){return;}
$h=@fopen($last,'rb');
if(!$h){return;}
$line=fgets($h);
fclose($h);
[$base,$state]=stats_parse_header($line);
if($state!=='split'){return;}
$current=stats_count_unique_in_file($last,true);
$total=$base+$current;
stats_write_header($last,$total,'split');
}

function stats_append_ip(string $prefix,string $ip,string $log_file,int $limit):void{
if(stats_ip_in_index($prefix,$ip)){return;}
$last=stats_find_last_file($prefix);
$exists=file_exists($last);
$h=@fopen($last,'ab');
if(!$h){stats_log($log_file,'ERR fopen append '.$last);return;}
if(!$exists){@chmod($last,0664);}
if(@fwrite($h,$ip."\n")===false){stats_log($log_file,'ERR fwrite '.$last);}
@fflush($h);
if(function_exists('fsync')){@fsync($h);}
fclose($h);
stats_index_add($prefix,$ip,$log_file);
stats_update_dynamic_header($prefix);
stats_split_if_needed($prefix,$log_file,$limit);
}

function stats_compute_count(string $prefix,string $count_file,string $log_file):void{
$last=stats_find_last_file($prefix);
if(!file_exists($last)){
@file_put_contents($count_file,"0\n");
@chmod($count_file,0664);
return;
}
$h=@fopen($last,'rb');
$base=0;
if($h){
$line=fgets($h);
if($line!==false){[$base]=stats_parse_header($line);}
fclose($h);
}
$current=stats_count_unique_in_file($last,true);
$total=$base+$current;
if(@file_put_contents($count_file,(string)$total."\n")===false){
stats_log($log_file,'ERR write count '.$count_file);
return;
}
@chmod($count_file,0664);
}

function stats_rotate(string $dir,string $log_file,int $retention_days):void{
if(!is_dir($dir)){return;}
$dh=@opendir($dir);
if(!$dh){return;}
$now=time();
$limit=$retention_days*86400;
while(($f=readdir($dh))!==false){
if($f==='.'||$f==='..'){continue;}
$path=$dir.'/'.$f;
if(!is_file($path)){continue;}
if($path===$log_file){continue;}
$mtime=filemtime($path);
if($mtime!==false&&$now-$mtime>$limit){@unlink($path);}
}
closedir($dh);
}

if(!stats_ensure_dir($stats_dir,$log_file)){return;}
$lock_h=@fopen($lock_file,'c+');
if(!$lock_h){stats_log($log_file,'ERR lock fopen');return;}
@chmod($lock_file,0664);
if(!@flock($lock_h,LOCK_EX|LOCK_NB)){fclose($lock_h);return;}

try{
stats_rotate($stats_dir,$log_file,$retention_days);
$dt=new DateTimeImmutable('now');
$day=$dt->format('Ymd');
$ip=$_SERVER['REMOTE_ADDR']??'0.0.0.0';
$ua=$_SERVER['HTTP_USER_AGENT']??'';
$anon=stats_anonymize_ip($ip,$ua,$day,$secret_key);
$titre_page_var=$titre_page??'Page';
$nom_page_var=$nom_page??'';
$page_key=$titre_page_var;
if(str_contains($nom_page_var,'listing')){$page_key.='_listing';}
$page_prefix=$stats_dir.'/ip_'.$page_key.'_'.$day;
$total_prefix=$stats_dir.'/ip_Total_'.$day;
stats_append_ip($page_prefix,$anon,$log_file,$ip_limit_bytes);
stats_append_ip($total_prefix,$anon,$log_file,$ip_limit_bytes);
$page_count_file=$stats_dir.'/'.$page_key.'_'.$day.'.txt';
$total_count_file=$stats_dir.'/total_'.$day.'.txt';
stats_compute_count($page_prefix,$page_count_file,$log_file);
stats_compute_count($total_prefix,$total_count_file,$log_file);
}catch(Throwable $e){
stats_log($log_file,'EXC '.$e->getMessage());
}

@flock($lock_h,LOCK_UN);
fclose($lock_h);
