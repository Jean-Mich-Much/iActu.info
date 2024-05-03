<?php
$titre_page = 'Sciences';
$nom_page = 'sciences';
$page_active = $nom_page;
$sites = 'Sciences';
$cache_secondes = 298;
?>

<?php if (!file_exists($nom_page.'.html') or filemtime($nom_page.'.html') < (time() - $cache_secondes) or !file_exists($nom_page.date("j").'.html')) { ?>

<?php ob_start(); ?>

<!DOCTYPE html>
<html lang="fr">

 <head>
  <?php @include 'structure/php/head.php'; ?>
 </head>

 <body id="body" lang="fr">

  <nav id="top">
   <?php @include 'structure/php/menutop.php'; ?>
  </nav>
  <?php @include 'structure/php/sousmenuhaut.php'; ?>
  <?php @include 'structure/php/functions/unique.php'; ?>
  <?php @include 'structure/php/functions/lit.php'; ?>

  <main id="main_sites">
   <article class="sites" id="articles_sites">
    <?php @include 'structure/php/sites/'.$sites.'.php'; ?>
   </article>
  </main>

  <?php @include 'structure/php/sousmenubas.php'; ?>
  <nav id="bot">
   <?php @include 'structure/php/menubot.php'; ?>
  </nav>

 </body>

</html>

<?php
$p = ob_get_clean();
if (substr_count($p, '<!DOCTYPE html>') === 1 &&
    substr_count($p, '</html>') === 1 &&
    substr_count($p, 'http') >= 50 &&
    strlen($p) >= 65536) {
    echo $p;
    file_put_contents($nom_page.'.html', $p);
    @chmod($nom_page.'.html', 0775);
    @copy($nom_page.'.html', $nom_page.date("j").'.html');
    @chmod($nom_page.date("j").'.html', 0775);
} else {
   readfile($nom_page.'.html');
   }} else {
      readfile($nom_page.'.html');
     } ?>
     <?php @include 'structure/php/functions/stats.php';
     