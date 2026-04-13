<?php
try{
$d=__DIR__.'/../stats/';
if(!is_dir($d))@mkdir($d,0775,true);
$ip=$_SERVER['REMOTE_ADDR']??'0';
$ymd=date('Ymd');
$s=hash('xxh3','GonKirua|'.$ymd,false);
$id=rtrim(strtr(base64_encode(hash('xxh3',$ip.'|'.$ymd.$s,true)),'+/','-_'),'=');
$hid=hash('xxh3',$id,false);

$f1="$d/id_$ymd.txt";
$f2="$d/visiteurs_$ymd.txt";
$f3="$d/visites_$ymd.txt";
$fi="$d/index_$ymd.txt";

foreach([$f1=>'',$f2=>'0',$f3=>'0',$fi=>'']as$f=>$v)if(!file_exists($f))file_put_contents($f,$v,LOCK_EX);

$h=fopen($fi,'c+');flock($h,LOCK_EX);
$idx=stream_get_contents($h);
$new=strpos($idx,$hid."\n")===false;
if($new){fseek($h,0,SEEK_END);fwrite($h,$hid."\n");}
flock($h,LOCK_UN);fclose($h);

if($new){
@file_put_contents($f1,$id."\n",FILE_APPEND);
$v=(int)@file_get_contents($f2);
@file_put_contents($f2,$v+1);
}

$v=(int)@file_get_contents($f3);
@file_put_contents($f3,$v+1);

if(@filesize($f1)>262144)@file_put_contents("$d/id_{$ymd}_".date('Hi').".txt.gz",gzencode(@file_get_contents($f1),1));

if(mt_rand(0,500)==0){
$fs=glob("$d/id_*.txt.gz");
if($fs&&count($fs)>320){
array_multisort(array_map('filemtime',$fs),SORT_ASC,$fs);
while(count($fs)>320)@unlink(array_shift($fs));
}
foreach(glob("$d/*")as$f)if(filemtime($f)<time()-2682000)@unlink($f);
}

}catch(Throwable $e){
@file_put_contents(__DIR__.'/../stats/stats.log',date('c').' '.$e->getMessage()."\n",FILE_APPEND);
}
