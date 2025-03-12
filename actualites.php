<?php
$aa = 'act';
$ab = 3600;if (!file_exists($aa . '.html') or filemtime($aa . '.html') < (time() - $ab) or !file_exists($aa . date("j") . '.html')) {@include $aa . '.php';} else {@readfile($aa . '.html');@include 'Structure/php/modules/stats.php';}
