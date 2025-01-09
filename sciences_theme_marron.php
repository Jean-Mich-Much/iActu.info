<?php
$titre_page = 'Sciences';
$nom_page = 'sciences';
$page_active = 'sciences';
$titre_page_active = '&#129514;&nbsp;Sciences';
$cache_secondes = 23;

$lien_accueil = 'index.php';
$lien_tech = 'technologie.php';
$lien_apple = 'apple.php';
$lien_jeux = 'jeux.php';
$lien_sciences = 'sciences.php';
$lien_actu = 'actualites.php';
$lien_tv = 'tv.php';

$lien_theme = 'sciences_2.php';

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
    <?php parse('futur', 'Fut', 'https://www.futura-sciences.com/', 151, 144, 'Futura',28,'https://iactu.info/recherche/p/i/?a=rss&get=f_36&user=Gon1Kirua&token=flux&hours=2400'); ?>

    <?php parse('tecsci', 'Tec', 'https://www.techno-science.net/', 144, 144, 'Techno-Science',33,'https://iactu.info/recherche/p/i/?a=rss&get=f_39&user=Gon1Kirua&token=flux&hours=2400'); ?>

<?php parse('sciav', 'ScA', 'https://www.sciencesetavenir.fr/', 144, 144, 'Sciences et Avenir',38,'https://iactu.info/recherche/p/i/?a=rss&get=f_38&user=Gon1Kirua&token=flux&hours=2400'); ?>

<?php parse('scipo', 'ScP', 'https://sciencepost.fr/', 144, 144, 'SciencePost',43,'https://iactu.info/recherche/p/i/?a=rss&get=f_37&user=Gon1Kirua&token=flux&hours=2400'); ?>

    <?php parse('get8', 'TFr', 'https://iactu.info/recherche/p/i/?a=normal&get=c_8', 227, 144, 'Autres sites',48,'https://iactu.info/recherche/p/i/?a=rss&get=c_8&user=Gon1Kirua&token=flux&hours=2400'); ?>

    <?php parse('get9', 'TUK', 'https://iactu.info/recherche/p/i/?a=normal&get=c_9', 221, 144, 'English',53,'https://iactu.info/recherche/p/i/?a=rss&get=c_9&user=Gon1Kirua&token=flux&hours=2400'); ?>
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
