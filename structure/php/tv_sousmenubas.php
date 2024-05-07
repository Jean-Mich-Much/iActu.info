<?php @include 'structure/php/messagebas.php'; ?>

<nav id="sousmenubas">

  <span id="SousMenuBasListeDonateurs">
    <form>
      <select aria-label="Liste des donateurs" id="SousMenuBasListeDonateursSelect" name="Liste des donateurs" size="1" title="Liste des donateurs">
        <option value="#SousMenuBasListeDonateursSelect" title="Donateur" aria-label="Donateur" selected="">👤 Jérémy B. 10€ - 05/04&nbsp;</option>
        <option value="#SousMenuBasListeDonateursSelect" title="Donateur" aria-label="Donateur">👤 Xavier M. 5€ - 05/04&nbsp;</option>
        <option value="#SousMenuBasListeDonateursSelect" title="Donateur" aria-label="Donateur">👤 Sebastien D. 5€ - 03/04&nbsp;</option>
        <option value="#SousMenuBasListeDonateursSelect" title="Liste des donateurs" aria-label="Liste des donateurs">👤 David J. 85€ - 29/03&nbsp;</option>
        <option value="#SousMenuBasListeDonateursSelect" title="Liste des donateurs" aria-label="Liste des donateurs">👤 Philippe D. M. 20€ - 29/03&nbsp;</option>
        <option value="#SousMenuBasListeDonateursSelect" title="Liste des donateurs" aria-label="Liste des donateurs">👤 Christophe J. 5€ - 29/03&nbsp;</option>
        <option value="#SousMenuBasListeDonateursSelect" title="Liste des donateurs" aria-label="Liste des donateurs">👤 Philippe D. 10€ - 25/03&nbsp;</option>
        <option value="#SousMenuBasListeDonateursSelect" title="Donateur" aria-label="Donateur">👤 Xavier M. 5€ - 05/03&nbsp;</option>
        <option value="#SousMenuBasListeDonateursSelect" title="Donateur" aria-label="Donateur">👤 Sebastien D. 5€ - 03/03&nbsp;</option>
      </select>
    </form>
  </span>

  <span id="SousMenuBasPaypal">
    <a href="https://www.paypal.com/donate/?hosted_button_id=QVKRNFGMPXYXY" target="_blank" id="SousMenuBasPaypalLien" aria-label="Faire un don, merci par avance 😊" alt="Faire un don, merci par avance 😊" title="Faire un don, merci par avance 😊">&nbsp;🎁Faire un don&nbsp;</a>
  </span>

  <span id="SousMenuBasStatsDivers">
    <form>
      <select aria-label="Stats et diver" id="SousMenuBasStatsDiversSelect" name="StatsDivers" size="1" title="Stats et divers" onChange="window.location.href=this.value">
        <option value="#SousMenuBasStatsDiversSelect" title="Stats" aria-label="Stats" selected="">📜 Stats et divers&nbsp;</option>
        <option value="#SousMenuBasStatsDiversSelect" title="Visiteurs uniques, chaque visiteur est compté une seule fois par jour" aria-label="Visiteurs uniques">📈 Visiteurs :
          <?php @readfile("Stats/visiteurs_jour_" . date('d') . ".txt"); ?>&nbsp;
        </option>
        <option value="#SousMenuBasStatsDiversSelect" title="Visiteurs uniques pour hier" aria-label="Visiteurs uniques pour hier">📈 Hier :
          <?php @readfile("Stats/visiteurs_jour_" . date('d', strtotime("-1 day")) . ".txt"); ?>&nbsp;
        </option>
        <option value="#SousMenuBasStatsDiversSelect" title="Visites" aria-label="Visites">📈 Visites :
          <?php @readfile("Stats/visites_jour_" . date('d') . ".txt"); ?>&nbsp;
        </option>
        <option value="#SousMenuBasStatsDiversSelect" title="Visites pour hier" aria-label="Visites pour hier">📈 Hier :
          <?php @readfile("Stats/visites_jour_" . date('d', strtotime("-1 day")) . ".txt"); ?>&nbsp;
        </option>
        <option value="https://github.com/Jean-Mich-Much/iActu.info" title="Version" aria-label="Version 15">📜 Version 15&nbsp;</option>
        <option value="https://github.com/Jean-Mich-Much/iActu.info" title="Lien GitHub" aria-label="GitHub">😸Lien code source&nbsp;</option>
        <option value="https://github.com/Jean-Mich-Much/iActu.info" title="Copyright" aria-label="© iactu.info 2003">📰 ©️ iactu.info 2003&nbsp;</option>
      </select>
    </form>
  </span>

</nav>