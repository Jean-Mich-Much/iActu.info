<?php
$pagephp = 'sci';
$updatepagephp = 3600;if (!file_exists($pagephp . '.html') or filemtime($pagephp . '.html') < (time() - $updatepagephp) or !file_exists($pagephp . date("j") . '.html')) {@include $pagephp . '.php';} else {@readfile($pagephp . '.html');@include 'Structure/php/modules/stats.php';}
