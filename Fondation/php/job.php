<?php declare(strict_types=1);

const JOB_MAX=8,JOB_TIMEOUT=115,JOB_LOG_MAX=512,JOB_IS_CLI=(PHP_SAPI==='cli');

$DIR=__DIR__;
$LOG=$DIR.'/../logs/job.log';

if(!is_dir($DIR.'/../logs')){@mkdir($DIR.'/../logs',0775,true);@chmod($DIR.'/../logs',0775);}
if(!file_exists($LOG)){@touch($LOG);@chmod($LOG,0664);}

$GLOBALS['_JOB_QUEUE']=[];
$GLOBALS['_JOB_CAPTURE']=false;
$GLOBALS['_JOB_LOG']=$LOG;

function job_log(string$m):void{
$l=$GLOBALS['_JOB_LOG']??null;
if(!$l)return;
if(is_file($l)){
$sz=filesize($l);
$mt=filemtime($l)?:time();
if($sz>JOB_LOG_MAX||time()-$mt>604800)@file_put_contents($l,'');
}
@file_put_contents($l,'['.date('Y-m-d H:i:s')."] $m\n",FILE_APPEND);
}

function debut_job():void{
$GLOBALS['_JOB_CAPTURE']=true;
$GLOBALS['_JOB_QUEUE']=[];
job_log('DEBUT JOB');
}

function job_call(string$f,array$a=[]):void{
if(!function_exists($f)&&!JOB_IS_CLI){}
if($GLOBALS['_JOB_CAPTURE'])$GLOBALS['_JOB_QUEUE'][]=['f'=>$f,'a'=>$a];
else{
try{@call_user_func_array($f,$a);job_log("CALL DIRECT $f OK");}
catch(Throwable$e){job_log("EX DIRECT $f: ".$e->getMessage());}
}
}

function fin_job():void{
$GLOBALS['_JOB_CAPTURE']=false;
job_log('FIN JOB');
_job_exec();
}

function _job_exec():void{
$q=&$GLOBALS['_JOB_QUEUE'];
if(empty($q))return;
if(JOB_IS_CLI&&function_exists('pcntl_fork'))_job_exec_cli($q);
else _job_exec_fpm($q);
}

function _job_exec_fpm(array$q):void{
foreach($q as$j){
$f=$j['f'];$a=$j['a'];
$cmd='php '.__DIR__.'/job_worker.php '.escapeshellarg($f);
foreach($a as$x)$cmd.=' '.escapeshellarg((string)$x);
$cmd.=' > /dev/null 2>&1 &';
exec($cmd);
usleep(8000);
}
}

function _job_exec_cli(array$q):void{
$run=[];
while(!empty($q)||!empty($run)){
while(!empty($q)&&count($run)<JOB_MAX){
$j=array_shift($q);$f=$j['f'];$a=$j['a'];
$pid=@pcntl_fork();
if($pid===-1){job_log("FORK FAIL $f");array_unshift($q,$j);break;}
if($pid===0){
try{@call_user_func_array($f,$a);echo"[OK] $f\n";job_log("CHILD OK $f");exit(0);}
catch(Throwable$e){job_log("EX CHILD $f: ".$e->getMessage());echo"[ERR] $f ".$e->getMessage()."\n";exit(1);}
}
$run[$pid]=['f'=>$f,'t'=>time()];
}
foreach($run as$p=>$i){
$r=pcntl_waitpid($p,$s,WNOHANG);
if($r>0){unset($run[$p]);continue;}
if(time()-$i['t']>JOB_TIMEOUT){
@posix_kill($p,SIGKILL);
job_log("TIMEOUT {$i['f']} pid:$p");
echo"[KILL] {$i['f']}\n";
unset($run[$p]);
}
}
usleep(100000);
}
}
