<?php declare(strict_types=1);
require __DIR__.'/job.php';
require __DIR__.'/parser/lit_rss.php';
require __DIR__.'/fusion_rss.php';

if(PHP_SAPI!=='cli')exit;

$f=$argv[1]??'';if($f==='')exit;
$a=array_slice($argv,2);

pcntl_signal(SIGALRM,function()use($f){job_log("TIMEOUT WORKER $f");exit(1);});
pcntl_alarm(JOB_TIMEOUT);

try{@call_user_func_array($f,$a);}
catch(Throwable$e){job_log("EX WORKER $f: ".$e->getMessage());}

pcntl_alarm(0);
