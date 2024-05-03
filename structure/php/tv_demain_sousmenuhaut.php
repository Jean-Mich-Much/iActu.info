<?php
$affiche_paypal_haut = false;
?>

<?php @include 'structure/php/messagehaut.php'; ?>

<nav id="sousmenu">

  <span>
      <a href="tv.php" title="📺Ce soir à la TV 😊" class="lientv">&nbsp;📺Ce soir&nbsp;</a>
  </span>

  <span>
      <a href="tv_programme.php" title="📺Programme TV 😊" class="lientv">&nbsp;📺Programme&nbsp;</a>
  </span>

  <span id="SousMenuHautDates">
    <form>
      <select aria-label="Liste des dates" id="SousMenuHautDatesSelect" name="Dates" size="1" title="Liste des dates" onChange="window.location.href=this.value">
        <option value="#haut" title="Liste des dates" aria-label="Liste des dates" selected="">📅&nbsp;Demain soir&nbsp;
        </option>
        <option value="tv.php" title="Aujourd’hui" aria-label="Aujourd’hui">📋&nbsp;Aujourd’hui&nbsp;</option>
        <option value="tv<?php echo date('j', strtotime('-1 days')) . '.html'; ?>" title="Hier" aria-label="Hier">📋&nbsp;Hier soir&nbsp;</option>
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