<?php
$titre_page = 'TV';
$nom_page = 'tv';
$page_active = 'tv';
$cache_secondes = 7195;
?>

<?php if (!file_exists($nom_page . '.html') or filemtime($nom_page . '.html') < (time() - $cache_secondes) or !file_exists($nom_page . date("j") . '.html')) { ?>

    <?php ob_start(); ?>

    <!DOCTYPE html>
    <html lang="fr">

        <head>
            <?php @include 'structure/php/tv_head.php'; ?>
        </head>

        <body id="body" lang="fr">

            <nav id="top">
                <?php @include 'structure/php/menutop.php'; ?>
            </nav>
            <?php @include 'structure/php/functions/unique.php'; ?>
            <?php @include 'structure/php/functions/lit_tv.php'; ?>

            <main id="main_sites">
                <article class="sites" id="articles_sites">
                    <!-- DEBUT -->
                    <div class="tv-menu">
                        <span id="tv-soiree"><a href="#tv-soiree">🟡 Soirée du <?php echo str_replace('0', 'dimanche', str_replace('1', 'lundi', str_replace('2', 'mardi', str_replace('3', 'mercredi', str_replace('4', 'jeudi', str_replace('5', 'vendredi', str_replace('6', 'samedi', date('w')))))))); ?>
          <?php echo str_replace('jour', '', str_replace('jour1jour', '1er', 'jour' . date('j') . 'jour')); ?>
          <?php echo str_replace('1', 'janvier', str_replace('2', 'février', str_replace('3', 'mars', str_replace('4', 'avril', str_replace('5', 'mai', str_replace('6', 'juin', str_replace('7', 'juillet', str_replace('8', 'août', str_replace('9', 'septembre', str_replace('10', 'octobre', str_replace('11', 'novembre', str_replace('12', 'décembre', date('n'))))))))))))); ?></a></span>
                        <span class="o60"><a href="#tv-programme">⚪ Programme</a></span>
                        <span class="o60"><a href="#tv-demain">⚪ Demain</a></span>
                    </div>
                    <div class="tv-grid">
                        <?php lit_tv('xmltv_tnt', '20:49:59', '23:59:59', '25', '2', '152', '52', false, true, true, true, '00:00:00', '02:59:59'); ?>
                    </div>
                    <!-- FIN -->
                    <!-- DEBUT -->
                    <div class="tv-menu">
                        <span class="o60"><a href="#tv-soiree">⚪ Soirée du <?php echo str_replace('0', 'dimanche', str_replace('1', 'lundi', str_replace('2', 'mardi', str_replace('3', 'mercredi', str_replace('4', 'jeudi', str_replace('5', 'vendredi', str_replace('6', 'samedi', date('w')))))))); ?>
          <?php echo str_replace('jour', '', str_replace('jour1jour', '1er', 'jour' . date('j') . 'jour')); ?>
          <?php echo str_replace('1', 'janvier', str_replace('2', 'février', str_replace('3', 'mars', str_replace('4', 'avril', str_replace('5', 'mai', str_replace('6', 'juin', str_replace('7', 'juillet', str_replace('8', 'août', str_replace('9', 'septembre', str_replace('10', 'octobre', str_replace('11', 'novembre', str_replace('12', 'décembre', date('n'))))))))))))); ?></a></span>
                        <span id="tv-programme"><a href="#tv-programme">🟡 Programme</a></span>
                        <span class="o60"><a href="#tv-demain">⚪ Demain</a></span>
                    </div>
                    <div class="tv-grid-programme">
                        <?php lit_tv('xmltv_tnt', '00:00:00', '23:59:59', '20', '72', '152', '28', false, true, false, false, '00:00:00', '05:59:59'); ?>
                    </div>
                    <!-- FIN -->
                    <!-- DEBUT -->
                    <div class="tv-menu">
                        <span class="o60"><a href="#tv-soiree">⚪ Soirée du <?php echo str_replace('0', 'dimanche', str_replace('1', 'lundi', str_replace('2', 'mardi', str_replace('3', 'mercredi', str_replace('4', 'jeudi', str_replace('5', 'vendredi', str_replace('6', 'samedi', date('w')))))))); ?>
          <?php echo str_replace('jour', '', str_replace('jour1jour', '1er', 'jour' . date('j') . 'jour')); ?>
          <?php echo str_replace('1', 'janvier', str_replace('2', 'février', str_replace('3', 'mars', str_replace('4', 'avril', str_replace('5', 'mai', str_replace('6', 'juin', str_replace('7', 'juillet', str_replace('8', 'août', str_replace('9', 'septembre', str_replace('10', 'octobre', str_replace('11', 'novembre', str_replace('12', 'décembre', date('n'))))))))))))); ?></a></span>
                        <span class="o60"><a href="#tv-programme">⚪ Programme</a></span>
                        <span id="tv-demain"><a href="#tv-demain">🟡 Demain</a></span>
                    </div>
                    <div class="tv-grid-demain">
                        <?php lit_tv('xmltv_tnt', '23:59:59', '23:59:59', '25', '1', '152', '52', false, true, true, true, '20:49:59', '23:59:59'); ?>
                    </div>
                    <!-- FIN -->
                </article>
            </main>
            <nav id="bot">
                <?php @include 'structure/php/menubot.php'; ?>
            </nav>

        </body>

    </html>

    <?php
    $p = ob_get_clean();
    if (
        substr_count($p, '<!DOCTYPE html>') === 1 &&
        substr_count($p, '</html>') === 1 &&
        substr_count($p, 'http') >= 10 &&
        strlen($p) >= 16384
    ) {
        echo $p;
        file_put_contents($nom_page . '.html', $p);
        @chmod($nom_page . '.html', 0775);
        @copy($nom_page . '.html', $nom_page . date("j") . '.html');
        @chmod($nom_page . date("j") . '.html', 0775);
    } else {
        readfile($nom_page . '.html');
    }
} else {
    readfile($nom_page . '.html');
} ?>
<?php @include 'structure/php/functions/stats.php'; ?>

<?php
if (@filemtime('copys.time') < (time() - 10795)) {
    @include 'copys.php';
}

