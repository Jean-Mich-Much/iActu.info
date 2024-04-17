<?php
$titre_page = 'TV';
$nom_page = 'tv_jeunesse';
$page_active = $nom_page;
$cache_secondes = 900;
?>

<?php if (!file_exists($nom_page.'.html') or filemtime($nom_page.'.html') < (time() - $cache_secondes) or !file_exists($nom_page.date("j").'.html')) { ?>

<?php ob_start(); ?>

<!DOCTYPE html>
<html lang="fr">

 <head>
  <?php @include 'structure/php/head.php'; ?>
  <?php @include 'structure/php/tv_head.php'; ?>
 </head>

 <body id="body" lang="fr">

  <nav id="haut">
   <?php @include 'structure/php/navhaut.php'; ?>
  </nav>
  <?php @include 'structure/php/sousmenuhaut.php'; ?>
  <?php @include 'structure/php/functions/unique.php'; ?>
  <?php @include 'structure/php/functions/lit_tv_jeunesse.php'; ?>

  <main id="main_sites">
   <article class="sites" id="articles_sites">
    
      <!-- DEBUT SITES PAR 2 -->
<div class="sitesPar2" <?php echo 'id="'.unique(2).unique(2).unique(2).'"'; ?>>
    <span class="siteGauche">
    <?php parse_complet('xmltv_tnt','WNe','https://iactu.info/tv.php',160,254,'Programme');?>
    </span>
</div><!-- FIN SITES PAR 2 -->

   </article>
  </main>

  <?php @include 'structure/php/sousmenubas.php'; ?>
  <nav id="bas">
   <?php @include 'structure/php/navbas.php'; ?>
  </nav>

 </body>

</html>

<?php
$p = ob_get_clean();
if (substr_count($p, '<!DOCTYPE html>') === 1 &&
    substr_count($p, '</html>') === 1 &&
    substr_count($p, 'http') >= 10 &&
    strlen($p) >= 8192) {
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
