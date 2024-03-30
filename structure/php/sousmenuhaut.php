<?php
$affiche_paypal_haut = false;
?>

<?php @include 'structure/php/messagehaut.php'; ?>

<nav id="sousmenu">

  <span id="SousMenuHautThemes">
    <form>
      <select aria-label="Thèmes" id="SousMenuHautThemesSelect" name="Thèmes" size="1" title="Liste des thèmes" onChange="window.location.href=this.value">
        <option value="#haut" title="Liste des thèmes" aria-label="Liste des thèmes" selected="">🎨&nbsp;Thèmes&nbsp;</option>
        <option value="https://iactu.info/" title="Lien thème clair" aria-label="Lien thème clair">🎨&nbsp;Clair&nbsp;</option>
        <option value="https://iactu.info/sombre.php" title="Lien thème sombre" aria-label="Lien thème sombre">🎨&nbsp;Sombre&nbsp;</option>
      </select>
    </form>
  </span>

  <span id="SousMenuHautStats_ou_Donateurs">
    &nbsp;
    <?php if ($affiche_paypal_haut) { ?>
      <form>
        <select aria-label="Liste des donateurs" id="SousMenuHautListeDonateurs" name="Liste des donateurs" size="1" title="Liste des donateurs">
          <option value="#haut" title="Liste des donateurs" aria-label="Liste des donateurs" selected="">👤 David J. 85€ - 29/03&nbsp;</option>
          <option value="#haut" title="Liste des donateurs" aria-label="Liste des donateurs">👤 Philippe D. M. 20€ - 29/03&nbsp;</option>
          <option value="#haut" title="Liste des donateurs" aria-label="Liste des donateurs">👤 Christophe J. 5€ - 29/03&nbsp;</option>
          <option value="#haut" title="Liste des donateurs" aria-label="Liste des donateurs">👤 Philippe D. 10€ - 25/03&nbsp;</option>
          <option value="#haut" title="Donateur" aria-label="Donateur">👤 Xavier M. 5€ - 05/03&nbsp;</option>
          <option value="#haut" title="Donateur" aria-label="Donateur">👤 Sebastien D. 5€ - 03/03&nbsp;</option>
        </select>
      </form>
      &nbsp;
    <?php } else { ?>

      <form>
        <select aria-label="Stats et diver" id="SousMenuHautStatsSelect" name="SousMenuStats" size="1" title="Stats" onChange="window.location.href=this.value">
          <option value="#haut" title="Stats" aria-label="Stats" selected="">📈 Statistiques&nbsp;</option>
          <option value="#haut" title="Visiteurs uniques, chaque visiteur est compté une seule fois par jour" aria-label="Visiteurs uniques">📈 Visiteurs :
            <?php @readfile("Stats/visiteurs_jour_" . date('d') . ".txt"); ?>&nbsp;
          </option>
          <option value="#haut" title="Visiteurs uniques pour hier" aria-label="Visiteurs uniques pour hier">📈 Hier :
            <?php @readfile("Stats/visiteurs_jour_" . date('d', strtotime("-1 day")) . ".txt"); ?>&nbsp;
          </option>
          <option value="#haut" title="Visites" aria-label="Visites">📈 Visites :
            <?php @readfile("Stats/visites_jour_" . date('d') . ".txt"); ?>&nbsp;
          </option>
          <option value="#haut" title="Visites pour hier" aria-label="Visites pour hier">📈 Hier :
            <?php @readfile("Stats/visites_jour_" . date('d', strtotime("-1 day")) . ".txt"); ?>&nbsp;
          </option>
        </select>
      </form>

    <?php } ?>
  </span>

  <span id="SousMenuHautDates">
    <form>
      <select aria-label="Liste des dates" id="SousMenuHautDatesSelect" name="Dates" size="1" title="Liste des dates" onChange="window.location.href=this.value">
        <option value="#haut" title="Liste des dates" aria-label="Liste des dates" selected="">📅&nbsp;
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


  <span id="SousMenuHautDivers_ou_Paypal">
    <?php if ($affiche_paypal_haut) { ?>
      &nbsp;
      <a href="https://www.paypal.com/donate/?hosted_button_id=QVKRNFGMPXYXY" target="_blank" id="SousMenuHautDonsPaypalHref" aria-label="Faire un don, merci par avance 😊" alt="Faire un don, merci par avance 😊" title="Faire un don, merci par avance 😊">&nbsp;🎁Faire un don (manque 85€)&nbsp;</a>
      &nbsp;
    <?php } else { ?>

      <form>
        <select aria-label="Divers" id="SousMenuHautDiversSelect" name="SousMenuDivers" size="1" title="Divers" onChange="window.location.href=this.value">
          <option value="#haut" title="Divers" aria-label="Divers" selected="">📜 Liens divers&nbsp;</option>
          <option value="https://github.com/Jean-Mich-Much/iActu.info" title="Lien GitHub" aria-label="GitHub">😸Code source&nbsp;</option>
          <option value="#coucou" title="Lien vers le bas de la page" aria-label="Lien vers le bas de la page">⬇️ Bas de page&nbsp;</option>
        </select>
      </form>

    <?php } ?>
  </span>

  <span id="SousMenuHautHeureMajPage">⏰&nbsp;Màj
    <?php echo date('H:i'); ?>
  </span>

</nav>
