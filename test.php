<?php
$titre_page='Test';
$nom_page='test';
$nom_page_theme_alternatif='theme_02_tv.php';
$page_active='tv';
$page_tv_active= 'cesoir';
$titre_page_active='&#129302;&nbsp;Tv';
$cache=5;
$theme='01';

if(!file_exists($nom_page.'.html')||filemtime($nom_page.'.html')<(time()-$cache)||!file_exists($nom_page.date("j").'.html')){ob_start();?>

<!DOCTYPE html>
<html lang="fr">

 <head><?php @include "Structure/php/modules/header.php"; ?><style type="text/css">
<?php @readfile("Structure/css/fonts.css");
@readfile("Structure/css/base.css");
@readfile("Structure/css/". $theme . ".css");
@readfile("Structure/css/tv.css");
?>
</style>
 </head>

<body id="body" lang="fr">
<div class="flex-page">
<div class="menu"><?php @include "Structure/php/modules/menu.php"; ?></div>
<div class="menu"><?php @include "Structure/php/modules/menu_tv.php"; ?></div>
<?php @include 'Structure/php/modules/messages_top.php'; ?>
<div class="mid">

<?php
@include "Structure/php/parsers/lit_tv_test.php";

// Charge les programmes TV depuis un fichier XMLTV
$programmes = tv(
    "Structure/cache/tv/xmltv_" . date('j') . ".xml", // Chemin du fichier XMLTV
    '2045', // Heure de début : 20h45
    '19',   // Durée minimale : 19 minutes
    '0',    // Jour : 0 = aujourd'hui, 1 = demain, etc.
    '2'     // Max programmes par chaîne : ici 2
);

// Affiche les programmes TV en HTML (0 = complet, 1 = compact)
afficherProgrammeTV($programmes, '0');
?>


 </div>

<div class="messages retour_ligne_on hauteur_auto"><?php @include 'Structure/php/modules/messages.php';?></div><?php @include 'Structure/php/modules/donateurs.php'; ?>
<div class="menu bot"><?php @include "Structure/php/modules/menu_tv.php"; ?></div>
<div class="menu bot"><?php @include 'Structure/php/modules/menu.php';?></div>
 </body>
 </html>
<?php $p=ob_get_clean();
if (substr_count($p,'<!DOCTYPE html>')===1 && substr_count($p,'</html>')===1 && strlen($p)>=1024) {
echo $p;
@file_put_contents($nom_page.'.html',$p);
@chmod($nom_page.'.html',0775);
@copy($nom_page.'.html',$nom_page.date("j").'.html');
@chmod($nom_page.date("j").'.html',0775);
} else {
@readfile($nom_page.'.html');
}} else {
@readfile($nom_page.'.html');
}
?>
<?php @include 'Structure/php/modules/stats.php';
