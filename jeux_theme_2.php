<?php
$titre_page = 'Jeux';
$nom_page = 'jeux_2';
$page_active = 'jeux';
$titre_page_active = 'Jeux';
$cache_secondes = 22;

$lien_accueil = 'index_2.php';
$lien_tech = 'technologie_2.php';
$lien_apple = 'apple_2.php';
$lien_jeux = 'jeux_2.php';
$lien_sciences = 'sciences_2.php';
$lien_actu = 'actualites_2.php';
$lien_tv = 'tv_2.php';

$lien_theme = 'jeux.php';

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

    <?php parse('gamek', 'Gam', 'https://www.gamekult.com/', 144, 144, 'Gamekult',27,'https://iactu.info/recherche/p/i/?a=rss&get=f_55&user=Gon1Kirua&token=flux&hours=2400'); ?>

    <?php parse('jvfra', 'Jvf', 'https://www.jvfrance.com/', 124, 144, 'JVFrance',32,'https://iactu.info/recherche/p/i/?a=rss&get=f_60&user=Gon1Kirua&token=flux&hours=2400'); ?>

<?php parse('facto', 'Fac', 'https://www.factornews.com/', 144, 144, 'Factornews',37,'https://iactu.info/recherche/p/i/?a=rss&get=f_54&user=Gon1Kirua&token=flux&hours=2400'); ?>

<?php parse('nfrag', 'NoF', 'https://nofrag.com/', 144, 144, 'NoFrag',42,'https://iactu.info/recherche/p/i/?a=rss&get=f_59&user=Gon1Kirua&token=flux&hours=2400'); ?>

<?php parse('gameg', 'GaG', 'https://www.gamergen.com/', 229, 144, 'GamerGen',47,'https://iactu.info/recherche/p/i/?a=rss&get=f_56&user=Gon1Kirua&token=flux&hours=2400'); ?>

<?php parse('indie', 'Ind', 'https://www.indiemag.fr/', 144, 144, 'IndieMag',52,'https://iactu.info/recherche/p/i/?a=rss&get=f_57&user=Gon1Kirua&token=flux&hours=2400'); ?>

    <?php parse('get12', 'TFr', 'https://iactu.info/recherche/p/i/?a=normal&get=c_12', 227, 144, 'Autres sites',57,'https://iactu.info/recherche/p/i/?a=rss&get=c_12&user=Gon1Kirua&token=flux&hours=2400'); ?>

    <?php parse('get13', 'TUK', 'https://iactu.info/recherche/p/i/?a=normal&get=c_13', 221, 144, 'English',62,'https://iactu.info/recherche/p/i/?a=rss&get=c_13&user=Gon1Kirua&token=flux&hours=2400'); ?>
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
