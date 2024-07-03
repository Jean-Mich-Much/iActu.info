<div class="top-logo"><a href="<?php echo $lien_accueil; ?>" title="Accueil" class="page_index" id="lienaccueilmenutop">&nbsp;</a></div>

<div class="top-menu">

 <div class="technologie"><a href="<?php echo $lien_tech; ?>" title="Technologie" class="page_technologie top_lien_menu <?php if ($page_active == 'technologie') {echo 'page_active'; } ?>" id="lientechmenutop">&nbsp;</a>

  <?php if ($page_active == 'technologie') { ?><div class="top-sub-menu">
   <div>&nbsp;<?php echo $titre_page_active; ?>&nbsp;</div>
   <div>&nbsp;📅&nbsp;<?php echo str_replace('0', 'Dimanche', str_replace('1', 'Lundi', str_replace('2', 'Mardi', str_replace('3', 'Mercredi', str_replace('4', 'Jeudi', str_replace('5', 'Vendredi', str_replace('6', 'Samedi', date('w')))))))); ?>&nbsp;<?php echo str_replace('jour', '', str_replace('jour1jour', '1er', 'jour' . date('j') . 'jour')); ?>&nbsp;<?php echo str_replace('1', 'janvier', str_replace('2', 'février', str_replace('3', 'mars', str_replace('4', 'avril', str_replace('5', 'mai', str_replace('6', 'juin', str_replace('7', 'juillet', str_replace('8', 'août', str_replace('9', 'septembre', str_replace('10', 'octobre', str_replace('11', 'novembre', str_replace('12', 'décembre', date('n'))))))))))))); ?>&nbsp;</div>
   <div>&nbsp;⏰&nbsp;Màj&nbsp;<?php echo date('H:i'); ?>&nbsp;</div>
  </div>
  <?php } ?>

 </div>

 <div class="apple"><a href="<?php echo $lien_apple; ?>" title="Apple" class="page_apple top_lien_menu <?php if ($page_active == 'apple') {echo 'page_active';} ?>" id="lienapplemenutop">&nbsp;</a>

  <?php if ($page_active == 'apple') { ?><div class="top-sub-menu">
   <div>&nbsp;<?php echo $titre_page_active; ?>&nbsp;</div>
   <div>&nbsp;📅&nbsp;<?php echo str_replace('0', 'Dimanche', str_replace('1', 'Lundi', str_replace('2', 'Mardi', str_replace('3', 'Mercredi', str_replace('4', 'Jeudi', str_replace('5', 'Vendredi', str_replace('6', 'Samedi', date('w')))))))); ?>&nbsp;<?php echo str_replace('jour', '', str_replace('jour1jour', '1er', 'jour' . date('j') . 'jour')); ?>&nbsp;<?php echo str_replace('1', 'janvier', str_replace('2', 'février', str_replace('3', 'mars', str_replace('4', 'avril', str_replace('5', 'mai', str_replace('6', 'juin', str_replace('7', 'juillet', str_replace('8', 'août', str_replace('9', 'septembre', str_replace('10', 'octobre', str_replace('11', 'novembre', str_replace('12', 'décembre', date('n'))))))))))))); ?>&nbsp;</div>
   <div>&nbsp;⏰&nbsp;Màj&nbsp;<?php echo date('H:i'); ?>&nbsp;</div>
  </div>
  <?php } ?>

 </div>

 <div class="jeux"><a href="<?php echo $lien_jeux; ?>" title="Jeux" class="page_jeux top_lien_menu  <?php if ($page_active == 'jeux') {echo 'page_active';} ?>" id="lienjeuxmenutop">&nbsp;</a>

  <?php if ($page_active == 'jeux') { ?><div class="top-sub-menu">
   <div>&nbsp;<?php echo $titre_page_active; ?>&nbsp;</div>
   <div>&nbsp;📅&nbsp;<?php echo str_replace('0', 'Dimanche', str_replace('1', 'Lundi', str_replace('2', 'Mardi', str_replace('3', 'Mercredi', str_replace('4', 'Jeudi', str_replace('5', 'Vendredi', str_replace('6', 'Samedi', date('w')))))))); ?>&nbsp;<?php echo str_replace('jour', '', str_replace('jour1jour', '1er', 'jour' . date('j') . 'jour')); ?>&nbsp;<?php echo str_replace('1', 'janvier', str_replace('2', 'février', str_replace('3', 'mars', str_replace('4', 'avril', str_replace('5', 'mai', str_replace('6', 'juin', str_replace('7', 'juillet', str_replace('8', 'août', str_replace('9', 'septembre', str_replace('10', 'octobre', str_replace('11', 'novembre', str_replace('12', 'décembre', date('n'))))))))))))); ?>&nbsp;</div>
   <div>&nbsp;⏰&nbsp;Màj&nbsp;<?php echo date('H:i'); ?>&nbsp;</div>
  </div>
  <?php } ?>

 </div>

 <div class="sciences"><a href="<?php echo $lien_sciences; ?>" title="Sciences" class="page_sciences top_lien_menu <?php if ($page_active == 'sciences') {echo 'page_active';} ?>" id="liensciencesmenutop">&nbsp;</a>

  <?php if ($page_active == 'sciences') { ?><div class="top-sub-menu">
   <div>&nbsp;<?php echo $titre_page_active; ?>&nbsp;</div>
   <div>&nbsp;📅&nbsp;<?php echo str_replace('0', 'Dimanche', str_replace('1', 'Lundi', str_replace('2', 'Mardi', str_replace('3', 'Mercredi', str_replace('4', 'Jeudi', str_replace('5', 'Vendredi', str_replace('6', 'Samedi', date('w')))))))); ?>&nbsp;<?php echo str_replace('jour', '', str_replace('jour1jour', '1er', 'jour' . date('j') . 'jour')); ?>&nbsp;<?php echo str_replace('1', 'janvier', str_replace('2', 'février', str_replace('3', 'mars', str_replace('4', 'avril', str_replace('5', 'mai', str_replace('6', 'juin', str_replace('7', 'juillet', str_replace('8', 'août', str_replace('9', 'septembre', str_replace('10', 'octobre', str_replace('11', 'novembre', str_replace('12', 'décembre', date('n'))))))))))))); ?>&nbsp;</div>
   <div>&nbsp;⏰&nbsp;Màj&nbsp;<?php echo date('H:i'); ?>&nbsp;</div>
  </div>
  <?php } ?>

 </div>

 <div class="actualites"><a href="<?php echo $lien_actu; ?>" title="Actualités" class="page_actualites top_lien_menu <?php if ($page_active == 'actualites') {echo 'page_active';} ?>" id="lienactumenutop">&nbsp;</a>

  <?php if ($page_active == 'actualites') { ?><div class="top-sub-menu">
   <div>&nbsp;<?php echo $titre_page_active; ?>&nbsp;</div>
   <div>&nbsp;📅&nbsp;<?php echo str_replace('0', 'Dimanche', str_replace('1', 'Lundi', str_replace('2', 'Mardi', str_replace('3', 'Mercredi', str_replace('4', 'Jeudi', str_replace('5', 'Vendredi', str_replace('6', 'Samedi', date('w')))))))); ?>&nbsp;<?php echo str_replace('jour', '', str_replace('jour1jour', '1er', 'jour' . date('j') . 'jour')); ?>&nbsp;<?php echo str_replace('1', 'janvier', str_replace('2', 'février', str_replace('3', 'mars', str_replace('4', 'avril', str_replace('5', 'mai', str_replace('6', 'juin', str_replace('7', 'juillet', str_replace('8', 'août', str_replace('9', 'septembre', str_replace('10', 'octobre', str_replace('11', 'novembre', str_replace('12', 'décembre', date('n'))))))))))))); ?>&nbsp;</div>
   <div>&nbsp;⏰&nbsp;Màj&nbsp;<?php echo date('H:i'); ?>&nbsp;</div>
  </div>
  <?php } ?>

 </div>

 <div class="tv"><a href="<?php echo $lien_tv; ?>" title="TV" class="page_tv top_lien_menu <?php if ($page_active == 'tv') {echo 'page_active';} ?>" id="lienresumemenutop">&nbsp;</a>

  <?php if ($page_active == 'tv') { ?><div class="top-sub-menu">
   <div>&nbsp;<?php echo $titre_page_active; ?>&nbsp;</div>
   <div>&nbsp;📅&nbsp;<?php echo str_replace('0', 'Dimanche', str_replace('1', 'Lundi', str_replace('2', 'Mardi', str_replace('3', 'Mercredi', str_replace('4', 'Jeudi', str_replace('5', 'Vendredi', str_replace('6', 'Samedi', date('w')))))))); ?>&nbsp;<?php echo str_replace('jour', '', str_replace('jour1jour', '1er', 'jour' . date('j') . 'jour')); ?>&nbsp;<?php echo str_replace('1', 'janvier', str_replace('2', 'février', str_replace('3', 'mars', str_replace('4', 'avril', str_replace('5', 'mai', str_replace('6', 'juin', str_replace('7', 'juillet', str_replace('8', 'août', str_replace('9', 'septembre', str_replace('10', 'octobre', str_replace('11', 'novembre', str_replace('12', 'décembre', date('n'))))))))))))); ?>&nbsp;</div>
   <div>&nbsp;⏰&nbsp;Màj&nbsp;<?php echo date('H:i'); ?>&nbsp;</div>
  </div>
  <?php } ?>

 </div>

 <div class="rec"><a href="recherche/p/i/" title="Recherche" class="page_rec top_lien_menu">&nbsp;</a></div>

</div>