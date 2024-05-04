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
            <?php @include 'structure/php/tv_sousmenuhaut.php'; ?>
            <?php @include 'structure/php/functions/unique.php'; ?>
            <?php @include 'structure/php/functions/lit_tv.php'; ?>

            <main id="main_sites">
                <article class="sites" id="articles_sites">
                    <!-- DEBUT -->
                    <div class="sitesPar2">
                        <span class="siteGauche">
                            <div class="boite">
                                <?php lit_tv('xmltv_tnt', 'tv-01', '20:49:59', '23:59:59', '25', '2', '188', '200', false, true, true, true); ?>
                            </div>
                        </span>
                    </div>
                    <!-- FIN -->
                </article>
            </main>

            <?php @include 'structure/php/tv_sousmenubas.php'; ?>
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
