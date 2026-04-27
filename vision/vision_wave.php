<?php
declare(strict_types=1);
/* vision_wave.php */
final class VisionWave
{private const FILE=__DIR__.'/wave.json';
private static array $wave=['charge'=>0.0,'priorite'=>'idle', 'pression'=>'low','lock'=>'free','ram'=>'stable','disk'=>'idle','op'=>'none'];
public static function init(): void
{if(!file_exists(self::FILE)){self::sync();return;}
$f=@fopen(self::FILE,'rb');if(!$f)return;
@flock($f,LOCK_SH);$json=@stream_get_contents($f);@flock($f,LOCK_UN);@fclose($f);
if($json&&($data=json_decode($json,true))){if(is_array($data))self::$wave=array_merge(self::$wave,$data);}}
public static function get(): array
{return self::$wave;}
public static function set(string $key,mixed $value): void
{if(array_key_exists($key,self::$wave)){self::$wave[$key]=$value;self::sync();}}
public static function update(array $changes): void
{foreach($changes as $k=>$v)if(array_key_exists($k,self::$wave))self::$wave[$k]=$v;self::sync();}
public static function sync(): void
{$lock=self::FILE.'.lock';$fl=@fopen($lock,'c');if(!$fl)return;if(!@flock($fl,LOCK_EX)){fclose($fl);return;}
try{$current=@file_get_contents(self::FILE);if($current&&($currentData=json_decode($current,true))){self::$wave=array_merge($currentData,self::$wave);}
$tmp=self::FILE.'.tmp';$json=json_encode(self::$wave,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
if($json===false)return;$f=@fopen($tmp,'wb');if($f){if(flock($f,LOCK_EX)){fwrite($f,$json);fflush($f);fsync($f);flock($f,LOCK_UN);}
fclose($f);@chmod($tmp,0664);@rename($tmp,self::FILE);}}finally{@flock($fl,LOCK_UN);@fclose($fl);}}}
VisionWave::init();
