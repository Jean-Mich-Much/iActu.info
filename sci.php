<?php
$titre_page = 'Sciences';
$nom_page = 'sci';
$nom_page_theme_alternatif = 'theme_02_sciences.php';
$page_active = 'sciences';
$titre_page_active = '&#129302;&nbsp;Sciences';
$sites = 'sci';
$cache_secondes = 9;
$theme = '01';
?>

<?php if (!file_exists($nom_page . '.html') or filemtime($nom_page . '.html') < (time() - $cache_secondes) or !file_exists($nom_page . date("j") . '.html')) { ?>

<?php ob_start(); ?>

<?php @include 'Structure/php/parsers/lit.php'; ?>

<!DOCTYPE html>
<html lang="fr">

 <head>
  <?php @include 'Structure/php/modules/header.php'; ?>
  <style type="text/css">
  <?php @readfile('Structure/css/fonts.css');
  @readfile('Structure/css/base.css');
  @readfile('Structure/css/'.$theme.'.css');
  ?>
  </style>

 </head>

 <body id="body" lang="fr">


  <div class="flex-page">

   <div class="menu">
    <?php @include 'Structure/php/modules/menu.php'; ?>
   </div>

   <div class="mid">
    <?php @include 'Structure/sites/'.$sites.'.php'; ?>
   </div>

   <div class="messages retour_ligne_on hauteur_auto">
    <?php @include 'Structure/php/modules/messages.php'; ?>
   </div>

   <div class="menu bot">
    <?php @include 'Structure/php/modules/menu.php'; ?>
   </div>

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
};