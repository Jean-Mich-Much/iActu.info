<?php
declare(strict_types=1);
/* vision_worker.php */

final class VisionWorker
{private static array $queue=[];
private static array $fibers=[];
private static bool $running=false;
public static function add(callable $task,string $type='write'): void
{self::$queue[]=['task'=>$task,'type'=>$type];}
public static function run(): void
{if(self::$running) return;
self::$running=true;
try{self::$fibers=array_values(array_filter(self::$fibers,fn($f)=>$f instanceof Fiber&&!$f->isTerminated()));
foreach(self::$fibers as $f){if($f->isSuspended()){try{$f->resume();}catch(Throwable $e){Vision::log("Worker Resume Error: ".$e->getMessage());}}}
$wave=VisionWave::get();while(count(self::$fibers)<16&&!empty(self::$queue)){$next=self::nextTask($wave);if(!is_callable($next))break;
$newFiber=new Fiber(function()use($next){try{($next)();}catch(Throwable $e){Vision::log("Worker Task Error: ".$e->getMessage());}});
try{$newFiber->start();if(!$newFiber->isTerminated())self::$fibers[]=$newFiber;}catch(Throwable $e){Vision::log("Fiber Startup Fail: ".$e->getMessage());break;}}}finally{self::$running=false;}}
private static function nextTask(array $wave): ?callable
{$prio=$wave['priorite']??'idle';foreach(self::$queue as $i=>$item){if(($item['type']??'')===$prio){$task=$item['task'];array_splice(self::$queue,$i,1);return $task;}}$item=array_shift(self::$queue);return $item?($item['task']??null):null;}}
