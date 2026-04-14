<?php
declare(strict_types=1);
$stats_dir=__DIR__.'/../stats';
$lock_file=$stats_dir.'/lock';
$log_file=$stats_dir.'/log.txt';
$ip_limit_bytes=256*1024;
$log_limit_bytes=32768;
$retention_days=31;
$secret_key='vision-stats-secret-2026';
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
$base_name=basename($base);
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
$base=$prefix.'.txt';
$files=stats_list_files_for_prefix($prefix);
if(empty($files)){return $base;}
return $files[array_key_last($files)];
}
function stats_parse_header(string $line):array{
$line=trim($line);
if($line===''||$line[0]!=='{'){return [0,'none'];}
$data=json_decode($line,true);
if(!is_array($data)||count($data)<2){return [0,'none'];}
$count=(int)$data[0];
$state=is_string($data[1])?$data[1]:'none';
return [$count,$state];
}
function stats_write_header(string $file,int $count,string $state):bool{
$lines=@file($file,FILE_IGNORE_NEW_LINES);
if($lines===false){return false;}
if(!empty($lines)&&str_starts_with((string)$lines[0],'{')){
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
$first=true;
while(($line=fgets($h))!==false){
$line=trim($line);
if($line===''){continue;}
if($first&&$skip_header&&str_starts_with($line,'{')){
$first=false;
continue;
}
$first=false;
$set[$line]=true;
}
fclose($h);
return count($set);
}
function stats_ip_exists_in_file(string $file,string $ip):bool{
$h=@fopen($file,'rb');
if(!$h){return false;}
$first=true;
while(($line=fgets($h))!==false){
$line=trim($line);
if($line===''){continue;}
if($first&&str_starts_with($line,'{')){
$first=false;
continue;
}
$first=false;
if($line===$ip){fclose($h);return true;}
}
fclose($h);
return false;
}
function stats_ip_exists_for_prefix(string $prefix,string $ip):bool{
$files=stats_list_files_for_prefix($prefix);
foreach($files as $file){
if(stats_ip_exists_in_file($file,$ip)){return true;}
}
return false;
}
function stats_split_if_needed(string $prefix,string $log_file,int $limit):void{
$last=stats_find_last_file($prefix);
if(!file_exists($last)){return;}
$size=filesize($last);
if($size===false||$size<=$limit){return;}
$files=stats_list_files_for_prefix($prefix);
$index=0;
foreach($files as $f){
if(preg_match('~_split_(\d+)\.txt$~',$f,$m)){
$idx=(int)$m[1];
if($idx>$index){$index=$idx;}
}
}
$prev=$last;
$prev_header_count=0;
$prev_header_state='none';
$h=@fopen($prev,'rb');
if($h){
$first_line=fgets($h);
if($first_line!==false){
[$prev_header_count,$prev_header_state]=stats_parse_header($first_line);
}
fclose($h);
}
$unique_in_prev=stats_count_unique_in_file($prev,true);
$total=$prev_header_count+$unique_in_prev;
if(!stats_write_header($prev,$total,'end')){
stats_log($log_file,'ERR write_header '.$prev);
}
$index++;
$new=$prefix.'_split_'.$index.'.txt';
$header='{"'.$total.'","split"}'."\n";
if(@file_put_contents($new,$header)===false){
stats_log($log_file,'ERR create split '.$new);
return;
}
@chmod($new,0664);
}
function stats_append_ip(string $prefix,string $ip,string $log_file,int $limit):void{
if(stats_ip_exists_for_prefix($prefix,$ip)){return;}
$last=stats_find_last_file($prefix);
$dir=dirname($last);
if(!is_dir($dir)){return;}
$exists=file_exists($last);
$h=@fopen($last,'ab');
if(!$h){
stats_log($log_file,'ERR fopen append '.$last);
return;
}
if(!$exists){
@chmod($last,0664);
}
if(@fwrite($h,$ip."\n")===false){
stats_log($log_file,'ERR fwrite '.$last);
}
@fflush($h);
if(function_exists('fsync')){@fsync($h);}
fclose($h);
stats_split_if_needed($prefix,$log_file,$limit);
}
function stats_compute_count(string $prefix,string $count_file,string $log_file):void{
$last=stats_find_last_file($prefix);
if(!file_exists($last)){
@file_put_contents($count_file,"0\n");
@chmod($count_file,0664);
return;
}
$files=stats_list_files_for_prefix($prefix);
$target=$last;
$base_count=0;
$state='none';
$h=@fopen($target,'rb');
if($h){
$line=fgets($h);
if($line!==false){[$base_count,$state]=stats_parse_header($line);}
fclose($h);
}
if($state==='end'){
for($i=count($files)-1;$i>=0;$i--){
$f=$files[$i];
$h=@fopen($f,'rb');
if(!$h){continue;}
$line=fgets($h);
fclose($h);
if($line===false){continue;}
[$c,$s]=stats_parse_header($line);
if($s==='split'){
$target=$f;
$base_count=$c;
break;
}
}
}
$current=stats_count_unique_in_file($target,true);
$total=$base_count+$current;
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
if($mtime===false){continue;}
if($now-$mtime>$limit){
@unlink($path);
}
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
