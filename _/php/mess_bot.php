<?php
$affiche_message_bot = false;
?>

<?php if ($affiche_message_bot) {?>

<aside class="bot">

 📣 Message 🤣.

</aside>

<?php }?>

<aside class="mess">

 <span>
  <form>
   <select aria-label="Dates" id="SousMenuDates" name="Dates" size="1" title="Dates" onChange="window.location.href=this.value">
    <option value="#SousMenuDates" title="Dates" aria-label="Dates" selected>📅&nbsp;
     <?php echo str_replace('0', 'Dimanche', str_replace('1', 'Lundi', str_replace('2', 'Mardi', str_replace('3', 'Mercredi', str_replace('4', 'Jeudi', str_replace('5', 'Vendredi', str_replace('6', 'Samedi', date('w')))))))); ?>
     <?php echo str_replace('jour', '', str_replace('jour1jour', '1er', 'jour' . date('j') . 'jour')); ?>
     <?php echo str_replace('1', 'janvier', str_replace('2', 'février', str_replace('3', 'mars', str_replace('4', 'avril', str_replace('5', 'mai', str_replace('6', 'juin', str_replace('7', 'juillet', str_replace('8', 'août', str_replace('9', 'septembre', str_replace('10', 'octobre', str_replace('11', 'novembre', str_replace('12', 'décembre', date('n'))))))))))))); ?>&nbsp;
    </option>
    <option value="<?php echo $nom_page . date('j', strtotime('-1 days')) . '.html'; ?>" title="Hier" aria-label="Hier">📋&nbsp;
     <?php echo str_replace('0', 'Dimanche', str_replace('1', 'Lundi', str_replace('2', 'Mardi', str_replace('3', 'Mercredi', str_replace('4', 'Jeudi', str_replace('5', 'Vendredi', str_replace('6', 'Samedi', date('w', strtotime('-1 days'))))))))); ?>
     <?php echo str_replace('jour', '', str_replace('jour1jour', '1er', 'jour' . date('j', strtotime('-1 days')) . 'jour')); ?>
     <?php echo str_replace('1', 'janvier', str_replace('2', 'février', str_replace('3', 'mars', str_replace('4', 'avril', str_replace('5', 'mai', str_replace('6', 'juin', str_replace('7', 'juillet', str_replace('8', 'août', str_replace('9', 'septembre', str_replace('10', 'octobre', str_replace('11', 'novembre', str_replace('12', 'décembre', date('n', strtotime('-1 days')))))))))))))); ?>
     &nbsp;
    </option>
    <option value="<?php echo $nom_page . date('j', strtotime('-2 days')) . '.html'; ?>" title=" J-2" aria-label="J-2">📋&nbsp;
     <?php echo str_replace('0', 'Dimanche', str_replace('1', 'Lundi', str_replace('2', 'Mardi', str_replace('3', 'Mercredi', str_replace('4', 'Jeudi', str_replace('5', 'Vendredi', str_replace('6', 'Samedi', date('w', strtotime('-2 days'))))))))); ?>
     <?php echo str_replace('jour', '', str_replace('jour1jour', '1er', 'jour' . date('j', strtotime('-2 days')) . 'jour')); ?>
     <?php echo str_replace('1', 'janvier', str_replace('2', 'février', str_replace('3', 'mars', str_replace('4', 'avril', str_replace('5', 'mai', str_replace('6', 'juin', str_replace('7', 'juillet', str_replace('8', 'août', str_replace('9', 'septembre', str_replace('10', 'octobre', str_replace('11', 'novembre', str_replace('12', 'décembre', date('n', strtotime('-2 days')))))))))))))); ?>
     &nbsp;
    </option>
    <option value="<?php echo $nom_page . date('j', strtotime('-3 days')) . '.html'; ?>" title=" J-3" aria-label="J-3">📋&nbsp;
     <?php echo str_replace('0', 'Dimanche', str_replace('1', 'Lundi', str_replace('2', 'Mardi', str_replace('3', 'Mercredi', str_replace('4', 'Jeudi', str_replace('5', 'Vendredi', str_replace('6', 'Samedi', date('w', strtotime('-3 days'))))))))); ?>
     <?php echo str_replace('jour', '', str_replace('jour1jour', '1er', 'jour' . date('j', strtotime('-3 days')) . 'jour')); ?>
     <?php echo str_replace('1', 'janvier', str_replace('2', 'février', str_replace('3', 'mars', str_replace('4', 'avril', str_replace('5', 'mai', str_replace('6', 'juin', str_replace('7', 'juillet', str_replace('8', 'août', str_replace('9', 'septembre', str_replace('10', 'octobre', str_replace('11', 'novembre', str_replace('12', 'décembre', date('n', strtotime('-3 days')))))))))))))); ?>
     &nbsp;
    </option>
    <option value="<?php echo $nom_page . date('j', strtotime('-4 days')) . '.html'; ?>" title=" J-4" aria-label="J-4">📋&nbsp;
     <?php echo str_replace('0', 'Dimanche', str_replace('1', 'Lundi', str_replace('2', 'Mardi', str_replace('3', 'Mercredi', str_replace('4', 'Jeudi', str_replace('5', 'Vendredi', str_replace('6', 'Samedi', date('w', strtotime('-4 days'))))))))); ?>
     <?php echo str_replace('jour', '', str_replace('jour1jour', '1er', 'jour' . date('j', strtotime('-4 days')) . 'jour')); ?>
     <?php echo str_replace('1', 'janvier', str_replace('2', 'février', str_replace('3', 'mars', str_replace('4', 'avril', str_replace('5', 'mai', str_replace('6', 'juin', str_replace('7', 'juillet', str_replace('8', 'août', str_replace('9', 'septembre', str_replace('10', 'octobre', str_replace('11', 'novembre', str_replace('12', 'décembre', date('n', strtotime('-4 days')))))))))))))); ?>
     &nbsp;
    </option>
    <option value="<?php echo $nom_page . date('j', strtotime('-5 days')) . '.html'; ?>" title=" J-5" aria-label="J-5">📋&nbsp;
     <?php echo str_replace('0', 'Dimanche', str_replace('1', 'Lundi', str_replace('2', 'Mardi', str_replace('3', 'Mercredi', str_replace('4', 'Jeudi', str_replace('5', 'Vendredi', str_replace('6', 'Samedi', date('w', strtotime('-5 days'))))))))); ?>
     <?php echo str_replace('jour', '', str_replace('jour1jour', '1er', 'jour' . date('j', strtotime('-5 days')) . 'jour')); ?>
     <?php echo str_replace('1', 'janvier', str_replace('2', 'février', str_replace('3', 'mars', str_replace('4', 'avril', str_replace('5', 'mai', str_replace('6', 'juin', str_replace('7', 'juillet', str_replace('8', 'août', str_replace('9', 'septembre', str_replace('10', 'octobre', str_replace('11', 'novembre', str_replace('12', 'décembre', date('n', strtotime('-5 days')))))))))))))); ?>
     &nbsp;
    </option>
    <option value="<?php echo $nom_page . date('j', strtotime('-6 days')) . '.html'; ?>" title=" J-6" aria-label="J-6">📋&nbsp;
     <?php echo str_replace('0', 'Dimanche', str_replace('1', 'Lundi', str_replace('2', 'Mardi', str_replace('3', 'Mercredi', str_replace('4', 'Jeudi', str_replace('5', 'Vendredi', str_replace('6', 'Samedi', date('w', strtotime('-6 days'))))))))); ?>
     <?php echo str_replace('jour', '', str_replace('jour1jour', '1er', 'jour' . date('j', strtotime('-6 days')) . 'jour')); ?>
     <?php echo str_replace('1', 'janvier', str_replace('2', 'février', str_replace('3', 'mars', str_replace('4', 'avril', str_replace('5', 'mai', str_replace('6', 'juin', str_replace('7', 'juillet', str_replace('8', 'août', str_replace('9', 'septembre', str_replace('10', 'octobre', str_replace('11', 'novembre', str_replace('12', 'décembre', date('n', strtotime('-6 days')))))))))))))); ?>
     &nbsp;
    </option>
    <option value="<?php echo $nom_page . date('j', strtotime('-7 days')) . '.html'; ?>" title=" J-7" aria-label="J-7">📋&nbsp;
     <?php echo str_replace('0', 'Dimanche', str_replace('1', 'Lundi', str_replace('2', 'Mardi', str_replace('3', 'Mercredi', str_replace('4', 'Jeudi', str_replace('5', 'Vendredi', str_replace('6', 'Samedi', date('w', strtotime('-7 days'))))))))); ?>
     <?php echo str_replace('jour', '', str_replace('jour1jour', '1er', 'jour' . date('j', strtotime('-7 days')) . 'jour')); ?>
     <?php echo str_replace('1', 'janvier', str_replace('2', 'février', str_replace('3', 'mars', str_replace('4', 'avril', str_replace('5', 'mai', str_replace('6', 'juin', str_replace('7', 'juillet', str_replace('8', 'août', str_replace('9', 'septembre', str_replace('10', 'octobre', str_replace('11', 'novembre', str_replace('12', 'décembre', date('n', strtotime('-7 days')))))))))))))); ?>
     &nbsp;
    </option>
   </select>
  </form>
 </span>

 <span>
  <form>
   <select aria-label="Donateurs" id="SousMenuBas" name="Donateurs" size="1" title="Donateurs">
   <option value="#SousMenuBas" title="Donateur" aria-label="Donateur" selected="">👤 Prénom Nom 5€ - 05/01&nbsp;</option>
   </select>
  </form>
 </span>

 <span>
  <a href="https://www.paypal.com/donate/?hosted_button_id=QVKRNFGMPXYXY" target="_blank" aria-label="Faire un don, merci par avance 😊" alt="Faire un don, merci par avance 😊" title="Faire un don, merci par avance 😊">&nbsp;💕Faire un don&nbsp;</a>
 </span>

 <span>
  <form>
   <select aria-label="Divers" id="SousMenuDivers" name="StatsDivers" size="1" title="Divers" onChange="window.location.href=this.value">
    <option value="#SousMenuDivers" title="Stats" aria-label="Stats" selected>📜 Divers&nbsp;</option>
    <option value="https://github.com/Jean-Mich-Much/iActu.info" title="Version" aria-label="Version 16">📜 Version 16&nbsp;</option>
    <option value="https://github.com/Jean-Mich-Much/iActu.info" title="Lien GitHub" aria-label="GitHub">😸Lien code source&nbsp;</option>
    <option value="https://github.com/Jean-Mich-Much/iActu.info" title="Copyright" aria-label="© iactu.info 2003">📰 ©️ iactu.info 2003&nbsp;</option>
   </select>
  </form>
 </span>

 <span id="coucou" class="coucou" data-user="33imej" data-website="moc.liamg" title="Coucou 😊" aria-label="Coucou 😊" role="term"></span>
</aside>