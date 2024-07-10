<div class="lien_top"><a href="<?php echo $lien_tech; ?>" title="Technologie" <?php if ($page_active == 'technologie') {echo 'class="page_active"';}?> id="lientechnologiemenutop">&nbsp;🤖<?php if ($page_active == 'technologie') {echo '&nbsp;' . $titre_page_active . '&nbsp;';}?></a></div>

<div class="lien_top"><a href="<?php echo $lien_apple; ?>" title="Apple" <?php if ($page_active == 'apple') {echo 'class="page_active"';}?> id="lienapplemenutop">&nbsp;🍏<?php if ($page_active == 'apple') {echo '&nbsp;' . $titre_page_active . '&nbsp;';}?></a></div>

<div class="lien_top"><a href="<?php echo $lien_jeux; ?>" title="Jeux" <?php if ($page_active == 'jeux') {echo 'class="page_active"';}?> id="lienjeuxmenutop">&nbsp;🕹️<?php if ($page_active == 'jeux') {echo '&nbsp;' . $titre_page_active . '&nbsp;';}?></a></div>

<div class="lien_top"><a href="<?php echo $lien_sciences; ?>" title="Sciences" <?php if ($page_active == 'sciences') {echo 'class="page_active"';}?> id="liensciencesmenutop">&nbsp;🧪<?php if ($page_active == 'sciences') {echo '&nbsp;' . $titre_page_active . '&nbsp;';}?></a></div>

<div class="lien_top"><a href="<?php echo $lien_actu; ?>" title="Actualités" <?php if ($page_active == 'actualites') {echo 'class="page_active"';}?> id="lienactualitesmenutop">&nbsp;🗞️<?php if ($page_active == 'actualites') {echo '&nbsp;' . $titre_page_active . '&nbsp;';}?></a></div>

<div class="lien_top"><a href="<?php echo $lien_tv; ?>" title="TV" <?php if ($page_active == 'tv') {echo 'class="page_active"';}?> id="lientvmenutop">&nbsp;📺<?php if ($page_active == 'tv') {echo '&nbsp;' . $titre_page_active . '&nbsp;';}?></a></div>

<div class="lien_top"><a href="recherche/p/i/" title="Recherche" id="lienrecherchemenutop">&nbsp;🔎&nbsp;</a></div>

<div class="txt_top">&nbsp;📅&nbsp;<?php echo str_replace('0', 'Dimanche', str_replace('1', 'Lundi', str_replace('2', 'Mardi', str_replace('3', 'Mercredi', str_replace('4', 'Jeudi', str_replace('5', 'Vendredi', str_replace('6', 'Samedi', date('w')))))))); ?>&nbsp;<?php echo str_replace('jour', '', str_replace('jour1jour', '1er', 'jour' . date('j') . 'jour')); ?>&nbsp;<?php echo str_replace('1', 'janvier', str_replace('2', 'février', str_replace('3', 'mars', str_replace('4', 'avril', str_replace('5', 'mai', str_replace('6', 'juin', str_replace('7', 'juillet', str_replace('8', 'août', str_replace('9', 'septembre', str_replace('10', 'octobre', str_replace('11', 'novembre', str_replace('12', 'décembre', date('n'))))))))))))); ?>&nbsp;</div>

<div class="txt_top">&nbsp;⏰&nbsp;Màj&nbsp;<?php echo date('H:i'); ?>&nbsp;</div>

<div class="lien_top"><a href="<?php echo $lien_theme; ?>" title="Thème 1" id="lienthememenutop">&nbsp;🎨 Thème 1&nbsp;</a></div>
