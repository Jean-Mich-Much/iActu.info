<?php
declare(strict_types=1);
/* Vision_API.php */
final class Vision_API
{private static function norm(array $rels): array
{$o=[];foreach($rels as $r){if(isset($r['id'],$r['niveau'])&&Vision_UniCell::isValidId((string)$r['id']))$o[]=['id'=>(string)$r['id'],'niveau'=>(int)$r['niveau']];}return $o;}
public static function create(array $data,array $relations=[],string $id=''): string
{$id=($id!==''&&Vision_UniCell::isValidId($id))?$id:Vision_UniCell::id();$now=time();$ok=false;$shard=Vision_UniCell::getShardName($id);$cell=['id'=>$id,'data'=>$data,'relations'=>self::norm($relations),'createdAt'=>$now,'updatedAt'=>$now];
VisionLock::acquire(function() use($id,$cell,&$ok)
{$ok=Vision_UniCell::write($id,$cell);if($ok)Vision::log('create '.$id);else Vision::log('create failed '.$id);},$shard);return $ok?$id:'';}
public static function read(string $id): ?array
{return Vision_UniCell::isValidId($id)?Vision_UniCell::read($id):null;}
public static function update(string $id,array $data,?array $relations=null): bool
{if(!Vision_UniCell::isValidId($id))return false;$ok=false;$shard=Vision_UniCell::getShardName($id);
VisionLock::acquire(function() use($id,$data,$relations,&$ok)
{$cell=Vision_UniCell::read($id);if(!$cell){Vision::log("Update Error: cell $id not found");return;}$cell['data']=$data;if($relations!==null)$cell['relations']=self::norm($relations);$cell['updatedAt']=time();$ok=Vision_UniCell::write($id,$cell);Vision::log('update '.$id);},$shard);return $ok;}
public static function delete(string $id): bool
{if(!Vision_UniCell::isValidId($id))return false;$ok=false;$shard=Vision_UniCell::getShardName($id);VisionLock::acquire(function()use($id,&$ok){$ok=Vision_UniCell::delete($id);Vision::log('delete '.$id);},$shard);return $ok;}
public static function exists(string $id): bool
{return Vision_UniCell::isValidId($id)&&Vision_UniCell::exists($id);}
public static function count(): int
{$n=0;foreach(Vision_UniCell::listCells() as $_){$n++;if($n%100===0&&Fiber::getCurrent())Fiber::suspend();}return $n;}
public static function stats(): array
{$total=0;$n=0;foreach(Vision_UniCell::listCells() as $id){$n++;if($n%100===0&&Fiber::getCurrent())Fiber::suspend();$path=Vision_UniCell::path($id);if(!file_exists($path))continue;$size=filesize($path);if($size!==false)$total+=$size;}return ['count'=>$n,'size'=>$total,'shards'=>count(Vision_UniCell::shards())];}
public static function relations(string $id): array
{$c=Vision_UniCell::read($id);return $c['relations']??[];}
public static function link(string $id,string $target,int $niveau): bool
{if(!Vision_UniCell::isValidId($id)||!Vision_UniCell::isValidId($target))return false;$ok=false;$shard=Vision_UniCell::getShardName($id);
VisionLock::acquire(function() use($id,$target,$niveau,&$ok)
{$c=Vision_UniCell::read($id);if(!$c){Vision::log("Link Error: source $id not found");return;}$rels=$c['relations']??[];$found=false;foreach($rels as &$r){if(($r['id']??null)===$target){$r['niveau']=$niveau;$found=true;break;}}if(!$found)$rels[]=['id'=>$target,'niveau'=>$niveau];$c['relations']=self::norm($rels);$c['updatedAt']=time();$ok=Vision_UniCell::write($id,$c);Vision::log('link '.$id.' '.$target);},$shard);return $ok;}
public static function unlink(string $id,string $target): bool
{if(!Vision_UniCell::isValidId($id))return false;$ok=false;$shard=Vision_UniCell::getShardName($id);
VisionLock::acquire(function() use($id,$target,&$ok)
{$c=Vision_UniCell::read($id);if(!$c){Vision::log("Unlink Error: source $id not found");return;}$rels=$c['relations']??[];$out=[];foreach($rels as $r){if(($r['id']??null)!==$target)$out[]=$r;}$c['relations']=$out;$c['updatedAt']=time();$ok=Vision_UniCell::write($id,$c);Vision::log('unlink '.$id.' '.$target);},$shard);return $ok;}
public static function graph(string $id): array
{$seen=[];$out=[];self::walk($id,$seen,$out,0);return $out;}
private static function walk(string $id,array &$seen,array &$out,int $d): void
{if(isset($seen[$id])||$d>16)return;$n=count($seen);if($n>0&&$n%50===0&&Fiber::getCurrent())Fiber::suspend();$c=Vision_UniCell::read($id);if(!$c)return;$seen[$id]=true;$out[$id]=$c;$rels=$c['relations']??[];foreach($rels as $r){if(!isset($r['id']))continue;self::walk($r['id'],$seen,$out,$d+1);}}
public static function find(callable $f): \Generator
{$n=0;foreach(Vision_UniCell::listCells() as $id){$n++;if($n%100===0&&Fiber::getCurrent())Fiber::suspend();$c=Vision_UniCell::read($id);if($c&&$f($c))yield $c;}}
public static function clean(): void
{VisionAutoclean::run();}}
