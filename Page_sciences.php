<?php
$titre_page='Sciences';
$nom_page='Page_sciences_jour_';
$page_active='sciences';
$page_listing = 'Page_sciences_listing';
$sites='sciences';
$cache_secondes=90;
$caracteres=12000;
$news_box='6';
$class_n='60';

$minute_pair=((int)date('i')%2===0);
$lock=__DIR__.'/maj_lock';

if(!file_exists($nom_page.'.html')
||!file_exists($nom_page.date("j").'.html')){
$minute_pair=true;
$force_maj=true;
}else{$force_maj=false;}

if(!$minute_pair){
@readfile($nom_page.'.html');
}else{

if(!file_exists($nom_page.'.html')
||filemtime($nom_page.'.html')<(time()-$cache_secondes)
||!file_exists($nom_page.date("j").'.html')){

ob_start();
@include 'Fondation/php/affiche_html.php';
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
@readfile('Fondation/css/mid.css');
?>
</style>
</head>
<body id="body" lang="fr">
<div class="flex">
<div class="top"><?php @include 'Fondation/php/menu.php';?></div>
<div class="messages_top"><?php @include 'Fondation/php/messages_top.php';?></div>
<div class="mid"><?php @include 'Fondation/sites/'.$sites.'.php';?></div>
<div class="messages_bot"><?php @include 'Fondation/php/messages_bot.php';?></div>
<div class="bot"><?php @include 'Fondation/php/menu.php';?></div>
</div>
</body>
</html>
<?php
$contenu=ob_get_clean();
if(substr_count($contenu,'<!DOCTYPE html>')===1
&&substr_count($contenu,'</html>')===1
&&substr_count($contenu,'class="news-box"')>=$news_box
&&substr_count($contenu,'class="n"')>=$class_n
&&strlen($contenu)>=$caracteres){
echo $contenu;
@file_put_contents($nom_page.'.html',$contenu);
@chmod($nom_page.'.html',0664);
@copy($nom_page.'.html',$nom_page.date("j").'.html');
@chmod($nom_page.date("j").'.html',0664);
}else{@readfile($nom_page.'.html');}
}else{@readfile($nom_page.'.html');}

}

if(!$minute_pair||$force_maj){
if(!file_exists($lock)||filemtime($lock)<time()-60){
@touch($lock);
@chmod($lock,0664);

$tempo_maj = __DIR__.'/maj_tempo';
$tempo_fusion = __DIR__.'/maj_fusion_tempo';
$delai = 655;
if (!file_exists($tempo_maj) || filemtime($tempo_maj) < time() - $delai) {exec("php maj.php > /dev/null 2>&1 &");};
if (!file_exists($tempo_fusion) || filemtime($tempo_fusion) < time() - $delai) {exec("php maj_fusion.php > /dev/null 2>&1 &");}
}}
