<?php
$titre_page = 'Apple';
$nom_page = 'apple_2';
$page_active = 'apple';
$titre_page_active = 'Apple';
$cache_secondes = 19;

$lien_accueil = 'index_2.php';
$lien_tech = 'technologie_2.php';
$lien_apple = 'apple_2.php';
$lien_jeux = 'jeux_2.php';
$lien_sciences = 'sciences_2.php';
$lien_actu = 'actualites_2.php';
$lien_tv = 'tv_2.php';

$lien_theme = 'apple.php';

?>

<?php if (!file_exists($nom_page . '.html') or filemtime($nom_page . '.html') < (time() - $cache_secondes) or !file_exists($nom_page . date("j") . '.html')) { ?>

<?php ob_start(); ?>

<?php @include '_/php/functions/unique.php'; ?>
<?php @include '_/php/functions/lit.php'; ?>

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
    <?php parse('macge', 'Mac', 'https://www.macg.co/', 144, 144, 'MacG',615,'https://iactu.info/recherche/p/i/?a=rss&get=f_75&user=Gon1Kirua&token=flux&hours=912'); ?>

    <?php parse('macbi', 'Mbi', 'http://macbidouille.com/', 240, 144, 'MacBidouille',715,'https://iactu.info/recherche/p/i/?a=rss&get=f_74&user=Gon1Kirua&token=flux&hours=912'); ?>

    <?php parse('conso', 'Csm', 'https://consomac.fr/', 144, 144, 'Consomac',815,'https://iactu.info/recherche/p/i/?a=rss&get=f_72&user=Gon1Kirua&token=flux&hours=912'); ?>

    <?php parse('mc4ve', 'M4e', 'https://www.mac4ever.com/', 91, 144, 'Mac4Ever',915,'https://iactu.info/recherche/p/i/?a=rss&get=f_73&user=Gon1Kirua&token=flux&hours=912'); ?>

    <?php parse('get16', 'TFr', 'https://iactu.info/recherche/p/i/?a=normal&get=c_16', 227, 144, 'Autres sites',1815,'https://iactu.info/recherche/p/i/?a=rss&get=c_16&user=Gon1Kirua&token=flux&hours=912'); ?>

    <?php parse('get21', 'TUK', 'https://iactu.info/recherche/p/i/?a=normal&get=c_17', 221, 144, 'English',1915,'https://iactu.info/recherche/p/i/?a=rss&get=c_17&user=Gon1Kirua&token=flux&hours=912'); ?>
   </main>

   <?php @include '_/php/mess_bot.php'; ?>

   <nav class="bot">
    <?php @include '_/php/bot.php'; ?>
   </nav>

  </div>

 </body>

</html>

<?php
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