<?php
declare(strict_types=1);

$VROOM_BASE_DIR=__DIR__.'/Fondation/vroom';
$VROOM_DATA_DIR=$VROOM_BASE_DIR.'/data';
$VROOM_LOG_DIR=$VROOM_BASE_DIR.'/logs';
$VROOM_PHP_DIR=$VROOM_BASE_DIR.'/php';
$VROOM_LOG_FILE=$VROOM_LOG_DIR.'/vroom.log';
$VROOM_MARKER='⟪⇒¦⇐⟫';

if(!is_dir($VROOM_BASE_DIR))mkdir($VROOM_BASE_DIR,0775,true);
if(!is_dir($VROOM_DATA_DIR))mkdir($VROOM_DATA_DIR,0775,true);
if(!is_dir($VROOM_LOG_DIR))mkdir($VROOM_LOG_DIR,0775,true);

require $VROOM_PHP_DIR.'/Vroom_state.php';
require $VROOM_PHP_DIR.'/Vroom_id.php';
require $VROOM_PHP_DIR.'/Vroom_lock.php';
require $VROOM_PHP_DIR.'/Vroom_disk_do.php';
require $VROOM_PHP_DIR.'/Vroom_disk_read.php';
require $VROOM_PHP_DIR.'/Vroom_ram.php';
require $VROOM_PHP_DIR.'/Vroom_exec.php';
require $VROOM_PHP_DIR.'/Vroom_job.php';
require $VROOM_PHP_DIR.'/Vroom_relations.php';

function create(string $type,array $data):int{
 global $VROOM_DATA_DIR;
 $id=vroom_next_id($type);
 $path=vroom_build_path($type,$id);
 vroom_ram_load_empty($type,$id,$path);
 vroom_ram_set_data($type,$id,$data);
 vroom_ram_set_header($type,$id,[
  'id'=>$id,
  'type'=>$type,
  'created_at'=>gmdate('c'),
  'updated_at'=>gmdate('c'),
  'parent_id'=>isset($data['parent_id'])?(string)$data['parent_id']:'',
  'user_id'=>isset($data['user_id'])?(string)$data['user_id']:'',
  'child_id'=>isset($data['child_id'])?(string)$data['child_id']:''
 ]);
 vroom_ram_commit_record($type,$id,$path);
 vroom_relations_after_save($type,$id);
 return $id;
}

function get(string $type,int $id):?array{
 $path=vroom_build_path($type,$id);
 if(!is_file($path))return null;
 vroom_disk_read_to_ram($type,$id,$path);
 vroom_relations_preload($type,$id);
 return vroom_ram_get_data($type,$id);
}

function all(string $type):array{
 global $VROOM_DATA_DIR;
 $dir=$VROOM_DATA_DIR.'/'.$type;
 if(!is_dir($dir))return [];
 $out=[];
 $it=new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir,FilesystemIterator::SKIP_DOTS));
 foreach($it as $file){
  if(substr($file->getFilename(),-5)!=='.vrec')continue;
  $id=(int)substr($file->getFilename(),0,-5);
  vroom_disk_read_to_ram($type,$id,$file->getPathname());
  vroom_relations_preload($type,$id);
  $out[$id]=vroom_ram_get_data($type,$id);
 }
 return $out;
}

function findBy(string $type,string $field,$value):array{
 $all=all($type);
 $out=[];
 foreach($all as $id=>$data){
  if(array_key_exists($field,$data)&&$data[$field]===$value)$out[$id]=$data;
 }
 return $out;
}

function update(string $type,int $id,array $data):bool{
 $path=vroom_build_path($type,$id);
 if(!is_file($path))return false;
 vroom_disk_read_to_ram($type,$id,$path);
 $current=vroom_ram_get_data($type,$id);
 foreach($data as $k=>$v)$current[$k]=$v;
 vroom_ram_set_data($type,$id,$current);
 $header=vroom_ram_get_header($type,$id);
 $header['updated_at']=gmdate('c');
 if(isset($current['parent_id']))$header['parent_id']=(string)$current['parent_id'];
 if(isset($current['user_id']))$header['user_id']=(string)$current['user_id'];
 if(isset($current['child_id']))$header['child_id']=(string)$current['child_id'];
 vroom_ram_set_header($type,$id,$header);
 vroom_ram_commit_record($type,$id,$path);
 vroom_relations_after_save($type,$id);
 return true;
}

function delete(string $type,int $id):bool{
 $path=vroom_build_path($type,$id);
 if(!is_file($path))return false;
 vroom_relations_before_delete($type,$id);
 vroom_disk_do_delete_now($type,$id,$path);
 vroom_ram_forget($type,$id);
 return true;
}

function begin():void{
 vroom_state_begin();
}

function commit():void{
 vroom_state_commit();
}

function rollback():void{
 vroom_state_rollback();
}

function info():array{
 return vroom_state_info();
}

function vroom_build_path(string $type,int $id):string{
 global $VROOM_DATA_DIR;
 $id_str=str_pad((string)$id,8,'0',STR_PAD_LEFT);
 $a=substr($id_str,0,2);
 $b=substr($id_str,2,2);
 $c=substr($id_str,4,2);
 $dir=$VROOM_DATA_DIR.'/'.$type.'/'.$a.'/'.$b.'/'.$c;
 if(!is_dir($dir))mkdir($dir,0775,true);
 return $dir.'/'.$id_str.'.vrec';
}
