<?php
$titre_page = 'iActu';
$nom_page = 'technologie_2';
$page_active = 'technologie';
$titre_page_active = 'Technologie';
$sites = 'Technologie';
$cache_secondes = 17;

$lien_accueil = 'index_2.php';
$lien_tech = 'technologie_2.php';
$lien_apple = 'apple_2.php';
$lien_jeux = 'jeux_2.php';
$lien_sciences = 'sciences_2.php';
$lien_actu = 'actualites_2.php';
$lien_tv = 'tv_2.php';

$lien_theme = 'technologie.php';

?>

<?php if (!file_exists($nom_page . '.html') or filemtime($nom_page . '.html') < (time() - $cache_secondes) or !file_exists($nom_page . date("j") . '.html')) {?>

<?php ob_start();?>

<?php @include '_/php/functions/unique.php';?>
<?php @include '_/php/functions/lit.php';?>

<!DOCTYPE html>
<html lang="fr">

 <head>
  <?php @include '_/php/header.php';?>
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

   <?php @include '_/php/mess_top.php';?>

   <main class="mid">
    <?php parse('clubi', 'Clu', 'https://www.clubic.com/', 144, 144, 'Clubic', 600, 'https://iactu.info/recherche/p/i/?a=rss&get=f_86&user=Gon1Kirua&token=flux&hours=912');?>

    <?php parse('nextim', 'Nx2', 'https://next.ink/', 72, 144, 'Next', 660, 'https://iactu.info/recherche/p/i/?a=rss&get=f_98&user=Gon1Kirua&token=flux&hours=912');?>

    <?php parse('gennt', 'GNT', 'http://www.generation-nt.com/', 144, 144, 'GNT', 720, 'https://iactu.info/recherche/p/i/?a=rss&get=f_91&user=Gon1Kirua&token=flux&hours=912');?>

    <?php parse('cowco', 'Cow', 'http://www.cowcotland.com/', 178, 144, 'Cowcotland', 780, 'https://iactu.info/recherche/p/i/?a=rss&get=f_87&user=Gon1Kirua&token=flux&hours=912');?>

    <?php parse('tomsh', 'ToH', 'https://www.tomshardware.fr/', 95, 144, 'Tom&#39;s Hardware', 840, 'https://iactu.info/recherche/p/i/?a=rss&get=f_105&user=Gon1Kirua&token=flux&hours=912');?>

    <?php parse('tomsg', 'ToG', 'https://www.tomsguide.fr/', 95, 144, 'Tom&#39;s Guide', 900, 'https://iactu.info/recherche/p/i/?a=rss&get=f_104&user=Gon1Kirua&token=flux&hours=912');?>

    <?php parse('ginjf', 'Gin', 'https://www.ginjfo.com/', 144, 144, 'GinjFo', 960, 'https://iactu.info/recherche/p/i/?a=rss&get=f_92&user=Gon1Kirua&token=flux&hours=912');?>

    <?php parse('compt', 'Com', 'https://www.comptoir-hardware.com/', 150, 144, 'Le Comptoir', 1020, 'https://iactu.info/recherche/p/i/?a=rss&get=f_95&user=Gon1Kirua&token=flux&hours=912');?>

    <?php parse('overc', 'Ove', 'https://overclocking.com/', 95, 144, 'Overclocking', 1080, 'https://iactu.info/recherche/p/i/?a=rss&get=f_101&user=Gon1Kirua&token=flux&hours=912');?>

    <?php parse('kultu', 'kug', 'https://kulturegeek.fr/', 103, 144, 'KultureGeek', 1140, 'https://iactu.info/recherche/p/i/?a=rss&get=f_110&user=Gon1Kirua&token=flux&hours=912');?>

    <?php parse('numer', 'Num', 'https://www.numerama.com/', 150, 144, 'Numerama', 1200, 'https://iactu.info/recherche/p/i/?a=rss&get=f_100&user=Gon1Kirua&token=flux&hours=912');?>

    <?php parse('fredz', 'Fzn', 'http://www.fredzone.org/', 144, 144, 'FZN', 1260, 'https://iactu.info/recherche/p/i/?a=rss&get=f_90&user=Gon1Kirua&token=flux&hours=912');?>

    <?php parse('jourdg', 'Jou', 'https://www.journaldugeek.com/', 126, 144, 'Journal du Geek', 1320, 'https://iactu.info/recherche/p/i/?a=rss&get=f_94&user=Gon1Kirua&token=flux&hours=912');?>

    <?php parse('lesnum', 'LNu', 'https://www.lesnumeriques.com/', 150, 144, 'Les Numériques', 1380, 'https://iactu.info/recherche/p/i/?a=rss&get=f_96&user=Gon1Kirua&token=flux&hours=912');?>

    <?php parse('presc', 'PCi', 'https://www.presse-citron.net/', 135, 144, 'Presse-citron', 1440, 'https://iactu.info/recherche/p/i/?a=rss&get=f_102&user=Gon1Kirua&token=flux&hours=912');?>

    <?php parse('infor', 'Inf', 'https://www.informaticien.be/', 144, 144, 'Informaticien', 1500, 'https://iactu.info/recherche/p/i/?a=rss&get=f_93&user=Gon1Kirua&token=flux&hours=912');?>

    <?php parse('devel', 'Dev', 'https://www.developpez.com/', 127, 144, 'Developpez', 1560, 'https://iactu.info/recherche/p/i/?a=rss&get=f_88&user=Gon1Kirua&token=flux&hours=912');?>

    <?php parse('siedi', 'SiD', 'https://siecledigital.fr/', 144, 144, 'Siècle Digital', 1620, 'https://iactu.info/recherche/p/i/?a=rss&get=f_103&user=Gon1Kirua&token=flux&hours=912');?>

    <?php parse('nexpi', 'Nep', 'https://www.nextpit.fr/', 144, 144, 'NextPit', 1680, 'https://iactu.info/recherche/p/i/?a=rss&get=f_99&user=Gon1Kirua&token=flux&hours=912');?>

    <?php parse('frand', 'FrA', 'https://www.frandroid.com/', 145, 144, 'FrAndroid', 1740, 'https://iactu.info/recherche/p/i/?a=rss&get=f_89&user=Gon1Kirua&token=flux&hours=912');?>

    <?php parse('get18', 'Rea', 'https://iactu.info/recherche/p/i/?a=normal&get=c_18', 144, 144, 'Réalité virtuelle', 1800, 'https://iactu.info/recherche/p/i/?a=rss&get=c_18&user=Gon1Kirua&token=flux&hours=912');?>

    <?php parse('get14', 'Lin', 'https://iactu.info/recherche/p/i/?a=normal&get=c_14', 144, 144, 'Linux', 1860, 'https://iactu.info/recherche/p/i/?a=rss&get=c_14&user=Gon1Kirua&token=flux&hours=912');?>

    <?php parse('get20', 'TFr', 'https://iactu.info/recherche/p/i/?a=normal&get=c_20', 227, 144, 'Autres sites', 1920, 'https://iactu.info/recherche/p/i/?a=rss&get=c_20&user=Gon1Kirua&token=flux&hours=912');?>

    <?php parse('get21', 'TUK', 'https://iactu.info/recherche/p/i/?a=normal&get=c_21', 221, 144, 'English', 1980, 'https://iactu.info/recherche/p/i/?a=rss&get=c_21&user=Gon1Kirua&token=flux&hours=912');?>
   </main>

   <?php @include '_/php/mess_bot.php';?>

   <nav class="bot">
    <?php @include '_/php/bot.php';?>
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
    }} else {
    @readfile($nom_page . '.html');
} ?>
<?php @include '_/php/functions/stats.php';
