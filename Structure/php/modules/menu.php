<?php
$lien_accueil = 'index.php';
$lien_tech = 'technologie.php';
$lien_apple = 'apple.php';
$lien_jeux = 'jeux.php';
$lien_sciences = 'sciences.php';
$lien_actu = 'actualites.php';
$lien_tv = 'tv.php';
$lien_rec = 'recherche/p/i/';

$emoji_theme_alternatif = '💡';

?>

<span class="pad8px m3px responsive">
<a class="logo" href="<?php echo $lien_accueil; ?>" title="Accueil"><span class="f18px m5px">🔖</span>iACTU<span class="logobarre m5px">&#124;</span><span class="logoinfo">INFO</span><span class="f28px flip45 m10px">🚀</span></a>
</span>

<span class="pad8px m3px">
<a class="menulien p3px <?php if ($page_active == 'technologie') {echo 'page_active'; } ?>" href="<?php echo $lien_tech; ?>" title="Technologie"><span class="menu_ico m3px">🤖</span><?php if ($page_active == 'technologie') {echo 'Tech.'; } ?></a>
</span>

<span class="pad8px m3px">
<a class="menulien p3px <?php if ($page_active == 'apple') {echo 'page_active'; } ?>" href="<?php echo $lien_apple; ?>" title="Apple"><span class="menu_ico m3px">🍏</span><?php if ($page_active == 'apple') {echo 'Apple'; } ?></a>
</span>

<span class="pad8px m3px">
<a class="menulien p3px <?php if ($page_active == 'jeux') {echo 'page_active'; } ?>" href="<?php echo $lien_jeux; ?>" title="Jeux"><span class="menu_ico m3px">🕹️</span><?php if ($page_active == 'jeux') {echo 'Jeux'; } ?></a>
</span>

<span class="pad8px m3px">
<a class="menulien p3px <?php if ($page_active == 'sciences') {echo 'page_active'; } ?>" href="<?php echo $lien_sciences; ?>" title="Sciences"><span class="menu_ico m3px">🧪</span><?php if ($page_active == 'sciences') {echo 'Sciences'; } ?></a>
</span>

<span class="pad8px m3px">
<a class="menulien p3px <?php if ($page_active == 'actu') {echo 'page_active'; } ?>" href="<?php echo $lien_actu; ?>" title="Actualités"><span class="menu_ico m3px">🗞️</span><?php if ($page_active == 'actu') {echo 'Info'; } ?></a>
</span>

<span class="pad8px m3px">
<a class="menulien p3px <?php if ($page_active == 'tv') {echo 'page_active'; } ?>" href="<?php echo $lien_tv; ?>" title="Tv"><span class="menu_ico m3px">📺️</span><?php if ($page_active == 'tv') {echo 'Tv'; } ?></a>
</span>

<span class="pad8px m3px">
<a class="menulien p3px" href="<?php echo $lien_rec; ?>" title="Recherche"><span class="menu_ico m3px">🔎</span></a>
</span>

<span class="menu_dates aligne responsive">
<a class="menu_dates menulien p1px aligne" href="<?php echo $nom_page . date('j', strtotime('-1 days')) . '.html'; ?>" title="Jour précédent"><span class="menu_dates aligne fw600">⬅️ J-1 📅&nbsp;&nbsp;<?php echo str_replace('0', 'Dimanche', str_replace('1', 'Lundi', str_replace('2', 'Mardi', str_replace('3', 'Mercredi', str_replace('4', 'Jeudi', str_replace('5', 'Vendredi', str_replace('6', 'Samedi', date('w')))))))); ?>&nbsp;<?php echo str_replace('jour', '', str_replace('jour1jour', '1er', 'jour' . date('j') . 'jour')); ?>&nbsp;<?php echo str_replace('1', 'janvier', str_replace('2', 'février', str_replace('3', 'mars', str_replace('4', 'avril', str_replace('5', 'mai', str_replace('6', 'juin', str_replace('7', 'juillet', str_replace('8', 'août', str_replace('9', 'septembre', str_replace('10', 'octobre', str_replace('11', 'novembre', str_replace('12', 'décembre', date('n'))))))))))))); ?></span></a>
</span>

<span class="menu_dates aligne responsive">
<a class="menu_dates menulien p1px aligne" href="<?php echo $nom_page . date('j', strtotime('+1 days')) . '.html'; ?>" title="Jour suivant"><span class="menu_dates aligne fw600">⏰&nbsp;<?php echo date('H:i'); ?>&nbsp;&nbsp;📅 J+1 ➡️</span></a>
</span>

<span class="pad8px m3px">
<a class="menulien p3px" href="<?php echo $nom_page_theme_alternatif; ?>" title="Plus clair"><span class="menu_ico m3px"><?php echo $emoji_theme_alternatif; ?></span></a>
</span>