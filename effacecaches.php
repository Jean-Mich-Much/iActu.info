<?php declare(strict_types=1);function effacecache(string $d,int $j):void{$i=$j*86400;$t=time();try{if(!is_dir($d)){throw new InvalidArgumentException("Dossier inexistant : $d");}foreach(new DirectoryIterator($d)as$f){if($f->isFile()){if($f->getMTime()<($t-$i)){unlink($f->getPathname());}}}}catch(Throwable $e){}}; ?>

<?php 
effacecache('Structure/cache/tv/images/cache/',120);
effacecache('Structure/cache/source/',120);
effacecache('Structure/cache/html/',120);
