

<?php if (!file_exists('tv_maj.html') or filemtime('tv_maj.html') < (time() - 15)) { ?>
<?php @include '_/php/functions/unique.php'; ?>
<?php @include '_/php/functions/lit_tv.php'; ?>
<?php ob_start(); ?>
<!-- DEBUT -->
<div class="tv-menu" id="tv-soir">
 <span><a href="#tv-soir">🟡 Ce soir&nbsp;📅&nbsp;<?php echo str_replace('0', 'Dimanche', str_replace('1', 'Lundi', str_replace('2', 'Mardi', str_replace('3', 'Mercredi', str_replace('4', 'Jeudi', str_replace('5', 'Vendredi', str_replace('6', 'Samedi', date('w')))))))); ?>&nbsp;<?php echo str_replace('jour', '', str_replace('jour1jour', '1er', 'jour' . date('j') . 'jour')); ?>&nbsp;<?php echo str_replace('1', 'janvier', str_replace('2', 'février', str_replace('3', 'mars', str_replace('4', 'avril', str_replace('5', 'mai', str_replace('6', 'juin', str_replace('7', 'juillet', str_replace('8', 'août', str_replace('9', 'septembre', str_replace('10', 'octobre', str_replace('11', 'novembre', str_replace('12', 'décembre', date('n'))))))))))))); ?></a></span>
 <span class="o60"><a href="#tv-demain">⚪ Demain&nbsp;📅&nbsp;<?php echo str_replace('0', 'Dimanche', str_replace('1', 'Lundi', str_replace('2', 'Mardi', str_replace('3', 'Mercredi', str_replace('4', 'Jeudi', str_replace('5', 'Vendredi', str_replace('6', 'Samedi', date('w', strtotime('+1 days'))))))))); ?>&nbsp;<?php echo str_replace('jour', '', str_replace('jour1jour', '1er', 'jour' . date('j', strtotime('+1 days')) . 'jour')); ?>&nbsp;<?php echo str_replace('1', 'janvier', str_replace('2', 'février', str_replace('3', 'mars', str_replace('4', 'avril', str_replace('5', 'mai', str_replace('6', 'juin', str_replace('7', 'juillet', str_replace('8', 'août', str_replace('9', 'septembre', str_replace('10', 'octobre', str_replace('11', 'novembre', str_replace('12', 'décembre', date('n', strtotime('+1 days')))))))))))))); ?></a></span>
</div>
<div class="tv-grid">
 <?php lit_tv('xmltv_tnt', '20:46:59', '23:59:59', '25', '2', '124', '44', false, true, true, true, '00:00:00', '07:59:59',false,'1'); ?>
</div>
<!-- FIN -->
<!-- DEBUT -->
<div class="tv-menu" id="tv-demain">
 <span class="o60"><a href="#tv-soir">⚪ Ce soir&nbsp;📅&nbsp;<?php echo str_replace('0', 'Dimanche', str_replace('1', 'Lundi', str_replace('2', 'Mardi', str_replace('3', 'Mercredi', str_replace('4', 'Jeudi', str_replace('5', 'Vendredi', str_replace('6', 'Samedi', date('w')))))))); ?>&nbsp;<?php echo str_replace('jour', '', str_replace('jour1jour', '1er', 'jour' . date('j') . 'jour')); ?>&nbsp;<?php echo str_replace('1', 'janvier', str_replace('2', 'février', str_replace('3', 'mars', str_replace('4', 'avril', str_replace('5', 'mai', str_replace('6', 'juin', str_replace('7', 'juillet', str_replace('8', 'août', str_replace('9', 'septembre', str_replace('10', 'octobre', str_replace('11', 'novembre', str_replace('12', 'décembre', date('n'))))))))))))); ?></a></span>
 <span><a href="#tv-demain">🟡 Demain&nbsp;📅&nbsp;<?php echo str_replace('0', 'Dimanche', str_replace('1', 'Lundi', str_replace('2', 'Mardi', str_replace('3', 'Mercredi', str_replace('4', 'Jeudi', str_replace('5', 'Vendredi', str_replace('6', 'Samedi', date('w', strtotime('+1 days'))))))))); ?>&nbsp;<?php echo str_replace('jour', '', str_replace('jour1jour', '1er', 'jour' . date('j', strtotime('+1 days')) . 'jour')); ?>&nbsp;<?php echo str_replace('1', 'janvier', str_replace('2', 'février', str_replace('3', 'mars', str_replace('4', 'avril', str_replace('5', 'mai', str_replace('6', 'juin', str_replace('7', 'juillet', str_replace('8', 'août', str_replace('9', 'septembre', str_replace('10', 'octobre', str_replace('11', 'novembre', str_replace('12', 'décembre', date('n', strtotime('+1 days')))))))))))))); ?></a></span>
</div>
<div class="tv-grid-demain">
<?php lit_tv('xmltv_tnt', '23:59:59', '23:59:59', '25', '2', '124', '44', false, true, true, true, '20:46:59', '23:59:59',true,'1'); ?>
</div>
<!-- FIN -->

<?php
$tv_maj = ob_get_clean();
if (
 substr_count($tv_maj, '_/cache/tv/') >= 15 &&
 substr_count($tv_maj, '<!-- FIN -->') >= 1 &&
 strlen($tv_maj) >= 1024
) {@file_put_contents('tv_maj.html', $tv_maj);copy('tv_maj.html','tv_maj_final.html');}}