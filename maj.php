<?php declare(strict_types=1);
if(PHP_SAPI==='cli'){
    require __DIR__.'/maj_cli.php';
    exit;
}

$cmd="php ".__DIR__."/maj_cli.php > /dev/null 2>&1 &";
exec($cmd);

http_response_code(204);
