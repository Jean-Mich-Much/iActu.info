<?php
$titre_page = 'Jeux';
$nom_page = 'jeux';
$page_active = 'jeux';
$titre_page_active = '&#128377;&#65039;&nbsp;Jeux';
$cache_secondes = 21;

$lien_accueil = 'index.php';
$lien_tech = 'technologie.php';
$lien_apple = 'apple.php';
$lien_jeux = 'jeux.php';
$lien_sciences = 'sciences.php';
$lien_actu = 'actualites.php';
$lien_tv = 'tv.php';

$lien_theme = 'jeux_2.php';

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
  <?php @readfile('_/css/root.css');
  @readfile('_/css/fonts.css');
  @readfile('_/css/body.css');
  @readfile('_/css/container.css');
  @readfile('_/css/top.css');
  @readfile('_/css/mid.css');
  @readfile('_/css/bot.css');
  @readfile('_/css/mess.css');
  @readfile('_/css/zindex.css');
  ?>
  </style>
 </head>

 <body id="body" lang="fr">

  <div class="container">

   <nav class="top" id="top">
    <?php @include '_/php/top.php'; ?>
   </nav>

   <?php @include '_/php/mess_top.php'; ?>

   <main class="mid">

    <?php parse('gamek', 'Gam', 'https://www.gamekult.com/', 144, 144, 'Gamekult',26,'https://iactu.info/recherche/p/i/?a=rss&get=f_55&user=Gon1Kirua&token=flux&hours=2400'); ?>

    <?php parse('jvfra', 'Jvf', 'https://www.jvfrance.com/', 124, 144, 'JVFrance',31,'https://iactu.info/recherche/p/i/?a=rss&get=f_60&user=Gon1Kirua&token=flux&hours=2400'); ?>

<?php parse('facto', 'Fac', 'https://www.factornews.com/', 144, 144, 'Factornews',36,'https://iactu.info/recherche/p/i/?a=rss&get=f_54&user=Gon1Kirua&token=flux&hours=2400'); ?>

<?php parse('nfrag', 'NoF', 'https://nofrag.com/', 144, 144, 'NoFrag',41,'https://iactu.info/recherche/p/i/?a=rss&get=f_59&user=Gon1Kirua&token=flux&hours=2400'); ?>

<?php parse('gameg', 'GaG', 'https://www.gamergen.com/', 229, 144, 'GamerGen',46,'https://iactu.info/recherche/p/i/?a=rss&get=f_56&user=Gon1Kirua&token=flux&hours=2400'); ?>

<?php parse('indie', 'Ind', 'https://www.indiemag.fr/', 144, 144, 'IndieMag',51,'https://iactu.info/recherche/p/i/?a=rss&get=f_57&user=Gon1Kirua&token=flux&hours=2400'); ?>

    <?php parse('get12', 'TFr', 'https://iactu.info/recherche/p/i/?a=normal&get=c_12', 227, 144, 'Autres sites',56,'https://iactu.info/recherche/p/i/?a=rss&get=c_12&user=Gon1Kirua&token=flux&hours=2400'); ?>

    <?php parse('get13', 'TUK', 'https://iactu.info/recherche/p/i/?a=normal&get=c_13', 221, 144, 'English',61,'https://iactu.info/recherche/p/i/?a=rss&get=c_13&user=Gon1Kirua&token=flux&hours=2400'); ?>
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
} ?>
<?php @include '_/php/functions/stats.php';
