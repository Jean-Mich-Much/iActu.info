<?php
declare(strict_types=1);
/* vision.php */
require __DIR__.'/Vision_UniCell.php';require __DIR__.'/Vision_API.php';require __DIR__.'/vision_wave.php';require __DIR__.'/vision_lock.php';require __DIR__.'/vision_worker.php';require __DIR__.'/vision_autoclean.php';
final class Vision
{private static bool $init = false;
private const LOG = __DIR__.'/vision.log';
public static function init(): void
{if (self::$init) return;
self::$init = true;
VisionWave::init();register_shutdown_function([Vision::class,'tick']);self::rotateLog();}
private static function getMemoryLimit(): int
{$limit=trim(ini_get('memory_limit'));if($limit==='-1')return 536870912;$unit=strtolower($limit[strlen($limit)-1]);$bytes=(int)$limit;if($unit==='g')$bytes*=1073741824;if($unit==='m')$bytes*=1048576;if($unit==='k')$bytes*=1024;return $bytes;}
public static function monitorMemory(): void
{$usage=memory_get_usage(true);$limit=self::getMemoryLimit();$pct=$usage/$limit;$state=VisionWave::get()['ram'];if($pct>0.75&&$state!=='critical'){VisionWave::set('ram','critical');self::log("RAM High Pressure: ".round($pct*100)."% - Cache Purged");}elseif($pct<0.50&&$state!=='stable'){VisionWave::set('ram','stable');self::log("RAM Stable: ".round($pct*100)."% - Cache Enabled");}}
private static function rotateLog(): void
{$max=32768;$old=time()-2592000;if(@file_exists(self::LOG)){if(@filesize(self::LOG)>$max)@file_put_contents(self::LOG,'',LOCK_EX);elseif(@filemtime(self::LOG)<$old)@file_put_contents(self::LOG,'',LOCK_EX);}else @file_put_contents(self::LOG,'',LOCK_EX);
@chmod(self::LOG,0664);}
public static function log(string $msg): void
{self::rotateLog();@file_put_contents(self::LOG,date('c').' '.$msg."\n",FILE_APPEND|LOCK_EX);}
public static function wave(): array
{return VisionWave::get();}
public static function setWave(string $key, mixed $value): void
{VisionWave::set($key,$value);
if(is_array($value)||is_object($value))$logValue=json_encode($value,JSON_UNESCAPED_UNICODE);elseif(is_bool($value))$logValue=$value?'true':'false';else $logValue=(string)$value;self::log("wave $key=$logValue");}
public static function updateWave(array $changes): void
{VisionWave::update($changes);self::log('wave update');}
public static function tick(): void
{self::monitorMemory();VisionWorker::run();VisionAutoclean::run();}}
Vision::init();
