<?php
$titre_page='Tv';
$nom_page='theme_02_tv_prog';
$nom_page_theme_alternatif='tv_prog.php';
$page_active='tv';
$page_tv_active= 'programme';
$titre_page_active='&#129302;&nbsp;Tv';
$cache=135;
$theme='01';
$lien_menu_theme = 'menu_theme_02';

if(!file_exists($nom_page.'.html')||filemtime($nom_page.'.html')<(time()-$cache)||!file_exists($nom_page.date("j").'.html')){ob_start();?>

<!DOCTYPE html>
<html lang="fr">

 <head><?php @include "Structure/php/modules/header.php"; ?><style type="text/css">
<?php @readfile("Structure/css/fonts.css");
@readfile("Structure/css/base.css");
@readfile("Structure/css/". $theme . ".css");
@readfile("Structure/css/tv_2.css");
 ?>
</style>
</head>
<body id="body" lang="fr">
<div class="flex-page">
<div class="menu"><?php @include 'Structure/php/modules/'.$lien_menu_theme.'.php'; ?></div>
<div class="menu"><?php @include "Structure/php/modules/menu_tv_theme_02.php"; ?></div>
<?php @include 'Structure/php/modules/messages_top.php'; ?>
<div class="mid">
<?php
@include "Structure/php/parsers/lit_tv_2.php";
$programmes = tv('Structure/cache/tv/xmltv_tnt.xml', date("Hi", strtotime("-360 minutes")), '2359', '5', '0', '360', '10');
// source du flux xmltv
// heure du début de la recherche des programmes tv
// heure de fin
// durée minimum d'un programme
// jour du programme tv : si 0 alors jour en cours mais si 1 = jour suivant par exemple / 2 dans deux jours etc.
// permet pas exemple pour 360 de rechercher jusqu'à 360 minutes en arrière de l'heure définit à rechercher un programme valide si aucun programme trouvé
// nombre maximum de programmes à rechercher
$maxProgrammes = 10;
// nombre maximum de programmes à afficher (inférieur ou égal au nombre de programmes à rechercher)
afficherProgrammeTV($programmes, $maxProgrammes);
?>
</div>
<div class="messages retour_ligne_on hauteur_auto"><?php @include 'Structure/php/modules/messages.php';?></div>
<div class="menu bot"><?php @include "Structure/php/modules/menu_tv_theme_02.php"; ?></div>
<div class="menu bot"><?php @include 'Structure/php/modules/'.$lien_menu_theme.'.php'; ?></div>
</body>
</html>
<?php $p=ob_get_clean();
if (substr_count($p,'<!DOCTYPE html>')===1 && substr_count($p,'</html>')===1 && strlen($p)>=1024) {
echo $p;
@file_put_contents($nom_page.'.html',$p);
@chmod($nom_page.'.html',0775);
@copy($nom_page.'.html',$nom_page.date("j").'.html');
@chmod($nom_page.date("j").'.html',0775);
} else {@readfile($nom_page.'.html');}} else {@readfile($nom_page.'.html');}
?><?php @include 'Structure/php/modules/stats.php';