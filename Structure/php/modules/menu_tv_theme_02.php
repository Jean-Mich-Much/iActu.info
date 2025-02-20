<?php
$lien_tv_base = 'theme_02_tv.php';
$lien_tv_demain = 'theme_02_tv_demain.php';
$lien_tv_prog = 'theme_02_tv_prog.php';
?>


</span>

<span class="pad8px m3px">
 <a class="menulien p3px <?php if ($page_tv_active == 'cesoir') {echo 'page_active'; } ?>" href="<?php echo $lien_tv_base; ?>" title="Ce soir"><span class="menu_ico m3px">📺</span>&nbsp;Ce soir&nbsp;</a>
</span>
</span>

<span class="pad8px m3px">
 <a class="menulien p3px <?php if ($page_tv_active == 'demain') {echo 'page_active'; } ?>" href="<?php echo $lien_tv_demain; ?>" title="Demain"><span class="menu_ico m3px">🎬</span>&nbsp;Demain&nbsp;</a>
</span>
</span>

<span class="pad8px m3px">
 <a class="menulien p3px <?php if ($page_tv_active == 'programme') {echo 'page_active'; } ?>" href="<?php echo $lien_tv_prog; ?>" title="Programme"><span class="menu_ico m3px">🎞️</span>&nbsp;Programme&nbsp;</a>
</span>
