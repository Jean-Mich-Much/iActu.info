<?php
$titre_page = 'TV';
$nom_page = 'tv_2';
$page_active = 'tv';
$titre_page_active = 'TV';
$cache_secondes = 37;
$cache_secondes_tv = 10800;

$lien_accueil = 'index_2.php';
$lien_tech = 'technologie_2.php';
$lien_apple = 'apple_2.php';
$lien_jeux = 'jeux_2.php';
$lien_sciences = 'sciences_2.php';
$lien_actu = 'actualites_2.php';
$lien_tv = 'tv_2.php';

$lien_theme = 'tv.php';

?>

<?php if (!file_exists($nom_page . '.html') or filemtime($nom_page . '.html') < (time() - $cache_secondes) or !file_exists($nom_page . date("j") . '.html')) { ?>

<?php ob_start(); ?>

<!DOCTYPE html>
<html lang="fr">

 <head>
  <?php @include '_/php/header.php'; ?>
  <style type="text/css">
  <?php @readfile('_/css/2/root.css');
  @readfile('_/css/2/fonts.css');
  @readfile('_/css/2/body.css');
  @readfile('_/css/2/container.css');
  @readfile('_/css/2/top.css');
  @readfile('_/css/2/mid.css');
  @readfile('_/css/2/bot.css');
  @readfile('_/css/2/mess.css');
  @readfile('_/css/2/zindex.css');
  @readfile('_/css/2/tv.css');
  ?>
  </style>
 </head>

 <body id="body" lang="fr">

  <div class="container">

   <nav class="top" id="top">
    <?php @include '_/php/2/top.php';?>
   </nav>

   <?php @include '_/php/mess_top.php'; ?>

   <main class="mid">
   <?php  if (file_exists('tv_maj_final_2.html')) {@include('tv_maj_final_2.html');}; ?>
   </main>

   <?php @include '_/php/mess_bot.php'; ?>

   <nav class="bot">
    <?php @include '_/php/bot.php'; ?>
   </nav>

  </div>

 </body>

</html>

<?php

if (!file_exists("_/cache/source/xmltv_tnt.xml") or @filemtime("_/cache/source/xmltv_tnt.tem") < (time() - $cache_secondes_tv)) {exec('wget -O "/_/_/cache/source/xmltv_tnt.xml" "https://xmltvfr.fr/xmltv/xmltv_tnt.xml" -q --limit-rate=16384k --no-check-certificate'. " > /dev/null &");if (!file_exists("_/cache/source/xmltv_tnt.time")){fopen("_/cache/source/xmltv_tnt.time", "w");} @copy("_/cache/source/xmltv_tnt.time", "_/cache/source/xmltv_tnt.tem");
}

exec('/usr/bin/php-cgi8.3 /_/tv_maj_2.php > /dev/null &');

 if (!file_exists("_/cache/source/tv.xml") or @filemtime("_/cache/source/xmltv_tnt.tem") < (time() - 300)) {exec('chmod 777 /_/_/cache/source/xmltv_tnt.xml && chown caddy:caddy /_/_/cache/source/xmltv_tnt.xml');
  $file_xml = file_get_contents('_/cache/source/xmltv_tnt.xml');
  if (
   substr_count($file_xml, '<tv') === 1 &&
   substr_count($file_xml, '</tv>') >= 1 &&
   strlen($file_xml) >= 1024
  ) {@unlink('_/cache/source/tv.xml');@file_put_contents('_/cache/source/tv.xml', $file_xml);}}

 $p = ob_get_clean();
 if (
  substr_count($p, '<!DOCTYPE html>') === 1 &&
  substr_count($p, '</html>') === 1 &&
  substr_count($p, 'http') >= 2 &&
  strlen($p) >= 1024
 ) {
  echo $p;
  @file_put_contents($nom_page . '.html', $p);
  @chmod($nom_page . '.html', 0775);
  @copy($nom_page . '.html', $nom_page . date("j") . '.html');
  @chmod($nom_page . date("j") . '.html', 0775);
 } else {
  @readfile($nom_page . '.html');
 }
} else {
 @readfile($nom_page . '.html');
}