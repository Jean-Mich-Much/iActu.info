<?php
declare(strict_types=1);
/* Vision_UniCell.php */

final class Vision_UniCell
{private const DIR=__DIR__.'/data';
private const CACHE_MAX=1000;
private static array $cache=[];
private static function ensureRoot(): void
{if(!is_dir(self::DIR))@mkdir(self::DIR,0775,true);@clearstatcache(true,self::DIR);if(is_dir(self::DIR)&&(fileperms(self::DIR)&0777)!==0775)@chmod(self::DIR,0775);}
public static function isValidId(string $id): bool
{return (bool)preg_match('/^(\d{8}_[a-f0-9]{12}|system_[a-z0-9_]+)$/',$id);}
public static function id(): string
{$date=date('Ymd');$rand=bin2hex(random_bytes(6));return $date.'_'.$rand;}
public static function getShardName(string $id): string
{if(str_starts_with($id,'system_'))return 'system';return self::isValidId($id)?substr($id,0,8):'00000000';}
private static function shard(string $id): string
{return self::DIR.'/'.self::getShardName($id);}
public static function path(string $id): string
{if(!self::isValidId($id))return self::DIR.'/null.vr';return self::shard($id).'/'.$id.'.vr';}
private static function ensure(string $dir): void
{if(!is_dir($dir))@mkdir($dir,0775,true);@clearstatcache(true,$dir);if(is_dir($dir)&&(fileperms($dir)&0777)!==0775)@chmod($dir,0775);}
public static function encode(array $cell): string
{try{$json=json_encode($cell,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR);
$base=base64_encode($json);$crc=dechex(crc32($json));$open="⟪¦";$close="¦⟫";
return "VISION1\n".$open.$base.$close."\n⟪Σ¦".$crc."¦⟫\n⟪⇒¦⇐⟫\n";
}catch(JsonException $e){Vision::log("Encode Error: ".$e->getMessage());return "";}}
public static function decode(string $raw): ?array
{if(strlen($raw)<30||!str_contains($raw,'⟪Σ¦'))return null;
$open="⟪¦";$close="¦⟫";$cOpen="⟪Σ¦";$lenOpen=strlen($open);$lenCOpen=strlen($cOpen);
$pos=strpos($raw,$open);$end=strpos($raw,$close,$pos);$cpos=strpos($raw,$cOpen);$cend=strpos($raw,'¦⟫',$cpos);
if($pos===false||$end===false||$cpos===false||$cend===false)return null;
$base=substr($raw,$pos+$lenOpen,$end-($pos+$lenOpen));
$crc=substr($raw,$cpos+$lenCOpen,$cend-($cpos+$lenCOpen));
$json=@base64_decode($base,true);
if($json===false||$json===''||!json_validate($json))return null;
if(dechex(crc32($json))!==$crc){Vision::log("CRC Integrity Error");return null;}
try{$cell=json_decode($json,true,512,JSON_THROW_ON_ERROR);return is_array($cell)?$cell:null;
}catch(JsonException $e){Vision::log("Decode Error: ".$e->getMessage());return null;}}
public static function read(string $id): ?array
{if(!self::isValidId($id))return null;$ram=VisionWave::get()['ram'];if($ram==='critical'){self::$cache=[];}elseif(isset(self::$cache[$id])){return self::$cache[$id];}
$path=self::path($id);if(!file_exists($path))return null;$raw=@file_get_contents($path);if($raw===false)return null;
try{$cell=self::decode($raw);if($cell&&$ram!=='critical'){if(count(self::$cache)>=self::CACHE_MAX){reset(self::$cache);unset(self::$cache[key(self::$cache)]);}self::$cache[$id]=$cell;}return $cell;}catch(Throwable $e){Vision::log("Read Critical Error on $id: ".$e->getMessage());return null;}}
public static function write(string $id,array $cell): bool
{if(!self::isValidId($id))return false;self::ensureRoot();$dir=self::shard($id);self::ensure($dir);$path=self::path($id);$tmp=$path.'.tmp';$data=self::encode($cell);
if($data==="")return false;unset(self::$cache[$id]);$f=@fopen($tmp,'wb');if(!$f)return false;if(!flock($f,LOCK_EX)){fclose($f);@unlink($tmp);return false;}
if(fwrite($f,$data)===false){flock($f,LOCK_UN);fclose($f);@unlink($tmp);return false;}fflush($f);fsync($f);flock($f,LOCK_UN);fclose($f);
@chmod($tmp,0664);if(!rename($tmp,$path))return false;
self::$cache[$id]=$cell;
if($df=@(PHP_OS_FAMILY==='Windows'?null:fopen($dir,'r'))){@fsync($df);@fclose($df);}
return true;}
public static function delete(string $id): bool
{$path=self::path($id);if(!file_exists($path))return false;$ok=unlink($path);if($ok)unset(self::$cache[$id]);if($ok&&$df=@fopen(self::shard($id),'r')){fsync($df);fclose($df);}return $ok;}
public static function exists(string $id): bool
{if(!self::isValidId($id))return false;if(isset(self::$cache[$id]))return true;return file_exists(self::path($id));}
public static function shards(): array
{self::ensureRoot();
$dirs=scandir(self::DIR);if(!is_array($dirs))return [];$out=[];foreach($dirs as $d){if(!preg_match('/^\d{8}$/',$d)&&$d!=='system')continue;$dir=self::DIR.'/'.$d;if(is_dir($dir))$out[]=$dir;}rsort($out);return $out;}
public static function listCells(): \Generator
{foreach(self::shards() as $dir){$files=scandir($dir);if(!is_array($files))continue;foreach($files as $f){if(str_ends_with($f,'.vr')&&is_file($dir.'/'.$f))yield substr($f,0,-3);}}}
}
