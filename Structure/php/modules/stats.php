<?php
$ip = @$_SERVER['REMOTE_ADDR'];
$day = @date('d');
$ipFile = "Structure/stats/ip_jour_$day.txt";
$visitorsFile = "Structure/stats/visiteurs_jour_$day.txt";
$visitsFile = "Structure/stats/visites_jour_$day.txt";

if (!@file_exists($ipFile)) {
 @file_put_contents($ipFile, '');
 @chmod($ipFile, 0775);
}

if (!@file_exists($visitorsFile)) {
 @file_put_contents($visitorsFile, '0');
 @chmod($visitorsFile, 0775);
}

if (!@file_exists($visitsFile)) {
 @file_put_contents($visitsFile, '0');
 @chmod($visitsFile, 0775);
}

$ips = @file_get_contents($ipFile);

if (@strpos($ips, $ip) === false) {
 @file_put_contents($ipFile, "<ip>$ip</ip>\n", FILE_APPEND);
 $visitors = @file_get_contents($visitorsFile);
 @file_put_contents($visitorsFile, $visitors + 1);
}

$visits = @file_get_contents($visitsFile);
@file_put_contents($visitsFile, $visits + 1);

if (@filesize($ipFile) > 262144) {
 $time = @date('Hi');
 $gzFile = "Structure/stats/ip_jour_{$day}_{$time}.txt.gz";
 $data = @implode("", @file($ipFile));
 $gzData = @gzencode($data, 1);
 $fp = @fopen($gzFile, "w");
 @fwrite($fp, $gzData);
 @fclose($fp);
}

$files = @glob("Structure/stats/ip_jour_*.txt.gz");

if (@count($files) > 64) {
 @array_multisort(@array_map('filemtime', $files), SORT_NUMERIC, SORT_ASC, $files);
 while (@count($files) > 64) {
  $file = @array_shift($files);
  @unlink($file);
 }
}
foreach (@glob("Structure/stats/*") as $file) {
 if (@filemtime($file) < @time() - 259200) {
  @unlink($file);
 }
}
