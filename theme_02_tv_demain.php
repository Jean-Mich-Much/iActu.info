<?php
$titre_page='Tv';
$nom_page='theme_02_tv_demain';
$nom_page_theme_alternatif='tv_demain.php';
$page_active='tv';
$page_tv_active= 'demain';
$titre_page_active='&#129302;&nbsp;Tv';
$cache=3645;
$theme='01';
$lien_menu_theme = 'menu_theme_02';

if(!file_exists($nom_page.'.html')||filemtime($nom_page.'.html')<(time()-$cache)||!file_exists($nom_page.date("j").'.html')||filemtime($nom_page.date("j").'.html')<(time()-$cache)){ob_start();?>
<!DOCTYPE html>
<html lang="fr">
<head><?php @include "Structure/php/modules/header.php"; ?><style type="text/css">
<?php @readfile ("Structure/css/fonts.css");
@readfile ("Structure/css/base.css");
@readfile ("Structure/css/". $theme . ".css");
@readfile ("Structure/css/tv_1.css");
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
@include "Structure/php/parsers/lit_tv.php";
// Récupère les programmes TV à partir d'un fichier XMLTV
$programmes = tv(
"Structure/cache/tv/xmltv_" . date('j') . ".xml", // Chemin vers le fichier XMLTV
'2049', // Heure de début souhaitée (format HHMM, ici 20h45)
'19', // Durée minimale des programmes en minutes
'1', // Décalage en jours (0 = aujourd'hui, 1 = demain, etc.)
'2', // Nombre maximum de programmes par chaîne
'0', // Mode en cours : 1 = commence au programme actuellement en cours de diffusion au lieu de l'heure de début souhaitée
'1' // Mode prime time : 1 = sélectionne les programmes en prime time qui se terminent en début de soirée, indépendamment de l'heure de début souhaitée
);
// Génère et affiche les programmes TV en HTML (0 = affichage complet, 1 = affichage compact)
afficherProgrammeTV($programmes,'0');
?>
</div>
<?php @include 'Structure/php/modules/messages.php'; ?><?php @include 'Structure/php/modules/donateurs.php'; ?>
<div class="menu bot"><?php @include "Structure/php/modules/menu_tv_theme_02.php"; ?></div>
<div class="menu bot"><?php @include 'Structure/php/modules/'.$lien_menu_theme.'.php'; ?></div>
</body>
</html>
<?php $p=ob_get_clean();
if (substr_count($p,'<!DOCTYPE html>')===1 && substr_count($p,'</html>')===1 && strlen($p)>=1024) {
echo $p;@file_put_contents($nom_page.'.html',$p);
@chmod($nom_page.'.html',0775);@copy($nom_page.'.html',$nom_page.date("j").'.html');
@chmod($nom_page.date("j").'.html',0775);} else {@readfile ($nom_page.'.html');}}
else {@readfile ($nom_page.'.html');}; ?>
<?php @include 'Structure/php/modules/stats.php';
