<?php
declare(strict_types=1);
/* vision_autoclean.php */

final class VisionAutoclean
{private static bool $running=false;
public static function run(): void
{if(self::$running) return;
self::$running=true;
$wave=VisionWave::get();
if($wave['disk']!=='idle'||$wave['charge']>=0.5){self::$running=false;return;}
VisionLock::acquire(function() use($wave)
{try {
$now=VisionWave::get();
if($now['disk']!=='idle'||$now['charge']>=0.5)return;
Vision::log("Autoclean starting...");
self::clean();
Vision::log("Autoclean finished.");
}finally{self::$running=false;}},'global');}
private static function clean(): void
{$shards=Vision_UniCell::shards();
foreach($shards as $dir)
{$files=scandir($dir);if(!is_array($files))continue;
$remaining=0;$count=0;
foreach($files as $f)
{if($f==='.'||$f==='..')continue;
$path=$dir.'/'.$f;
if(str_ends_with($f,'.tmp')){if(time()-@filemtime($path)>3600)@unlink($path);else $remaining++;continue;}
if(!is_file($path)) { $remaining++; continue; }
$size=@filesize($path);
if($size===0) @unlink($path);
else $remaining++;
$count++;if($count%100===0&&Fiber::getCurrent())Fiber::suspend();}
if($remaining===0)@rmdir($dir);}}}
