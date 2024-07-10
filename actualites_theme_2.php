<?php
$titre_page = 'Actualités';
$nom_page = 'actualites_2';
$page_active = 'actualites';
$titre_page_active = 'Actualités';
$cache_secondes = 31;

$lien_accueil = 'index_2.php';
$lien_tech = 'technologie_2.php';
$lien_apple = 'apple_2.php';
$lien_jeux = 'jeux_2.php';
$lien_sciences = 'sciences_2.php';
$lien_actu = 'actualites_2.php';
$lien_tv = 'tv_2.php';

$lien_theme = 'actualites.php';

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

    <?php parse('actu4', 'TFr', 'https://iactu.info/recherche/p/i/?a=normal&get=c_4', 227, 144, 'France',1230,'https://iactu.info/recherche/p/i/?a=rss&get=c_4&user=Gon1Kirua&token=flux&hours=912'); ?>

    <?php parse('actu6', 'Imo', 'https://iactu.info/recherche/p/i/?a=normal&get=c_6', 114, 144, 'Monde',1330,'https://iactu.info/recherche/p/i/?a=rss&get=c_6&user=Gon1Kirua&token=flux&hours=912'); ?>

    <?php parse('actu5', 'Iin', 'https://iactu.info/recherche/p/i/?a=normal&get=c_5', 194, 144, 'Insolites',1230,'https://iactu.info/recherche/p/i/?a=rss&get=c_5&user=Gon1Kirua&token=flux&hours=912'); ?>

    <?php parse('actu2', 'TvC', 'https://iactu.info/recherche/p/i/?a=normal&get=c_2', 154, 144, 'TV - Cinéma',1330,'https://iactu.info/recherche/p/i/?a=rss&get=c_2&user=Gon1Kirua&token=flux&hours=912'); ?>

    <?php parse('actu10', 'Spo', 'https://iactu.info/recherche/p/i/?a=normal&get=c_10', 178, 144, 'Sports',1230,'https://iactu.info/recherche/p/i/?a=rss&get=c_10&user=Gon1Kirua&token=flux&hours=912'); ?>

    <?php parse('actu3', 'TUK', 'https://iactu.info/recherche/p/i/?a=normal&get=c_3', 221, 144, 'English',1330,'https://iactu.info/recherche/p/i/?a=rss&get=c_3&user=Gon1Kirua&token=flux&hours=912'); ?>
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