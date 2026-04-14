<?php
$titre_page='Stats';
$nom_page='Page_stats_jour_';
$cache_secondes=5;
$caracteres=500;

$cache_file=$nom_page.'.html';
$daily_file=$nom_page.date('j').'.html';
$regen=false;

if(!file_exists($cache_file)){$regen=true;}
elseif(filemtime($cache_file)<(time()-$cache_secondes)){$regen=true;}
elseif(!file_exists($daily_file)){$regen=true;}

if($regen){
ob_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<?php @include 'Fondation/php/header.php'; ?>
<style>
<?php
@readfile('Fondation/css/fonts.css');
@readfile('Fondation/css/base.css');
@readfile('Fondation/css/menu.css');
@readfile('Fondation/css/messages.css');
@readfile('Fondation/css/stats.css');
?>
</style>
</head>
<body id="body" lang="fr">
<div class="flex">
<div class="top"><?php @include 'Fondation/php/menu_stats.php';?></div>
<div class="messages_top"><?php @include 'Fondation/php/messages_top.php';?></div>
<div class="mid"><?php @include 'Fondation/php/affiche_stats.php';?></div>
<div class="messages_bot"><?php @include 'Fondation/php/messages_bot.php';?></div>
<div class="bot"><?php @include 'Fondation/php/menu.php';?></div>
</div>
</body>
</html>
<?php
$contenu=ob_get_clean();

if(substr_count($contenu,'<!DOCTYPE html>')===1
&& substr_count($contenu,'</html>')===1
&& strlen($contenu)>=$caracteres){

echo $contenu;

@file_put_contents($cache_file,$contenu);
@chmod($cache_file,0664);

@copy($cache_file,$daily_file);
@chmod($daily_file,0664);

}else{
@readfile($cache_file);
}

}else{
@readfile($cache_file);
}
