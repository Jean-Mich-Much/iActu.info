<nav id="sousmenubas">

  <?php @include 'structure/php/messagebas.php'; ?>

  <span id="lienjourprecedent">
    <a href="<?php echo $nom_page . date('j', strtotime('-1 days')) . '.html'; ?>" title="Actualités du jour précédent" aria-label="Actualités du jour précédent" id="JourPrecedent">⬅️ Jour précédent&nbsp;</a>
  </span>

  <span id="formlistedonateurs">
    <form>
      <select aria-label="Liste des donateurs" id="ListeDonateurs" name="Liste des donateurs" size="1" title="Liste des donateurs">
        <option title="Liste des donateurs" aria-label="Liste des donateurs" selected="">👤 Philippe D. 10€ - 25/03&nbsp;</option>
        <option title="Donateur" aria-label="Donateur">👤 Xavier M. 5€ - 05/03&nbsp;</option>
        <option title="Donateur" aria-label="Donateur">👤 Sebastien D. 5€ - 03/03&nbsp;</option>
      </select>
    </form>
  </span>

  <span id="lienpaypal">
    <a href="https://www.paypal.com/donate/?hosted_button_id=QVKRNFGMPXYXY" target="_blank" id="Faireundon" aria-label="Faire un don, merci par avance 😊" alt="Faire un don, merci par avance 😊" title="Faire un don, merci par avance 😊">&nbsp;🎁Faire un don&nbsp;</a>
  </span>

  <span id="formautres">
    <form>
      <select aria-label="Autres" id="FormSelectAutres" name="Autres" size="1" title="Autres" onChange="window.location.href=this.value">
        <option value="#FormSelectAutres" title="Stats" aria-label="Stats" selected="">📜 Stats et divers&nbsp;</option>
        <option value="#FormSelectAutres" title="Visiteurs uniques, chaque visiteur est compté une seule fois par jour" aria-label="Visiteurs uniques">📈 Visiteurs :
          <?php @readfile("Stats/visiteurs_jour_" . date('d') . ".txt"); ?>&nbsp;
        </option>
        <option value="#FormSelectAutres" title="Visiteurs uniques pour hier" aria-label="Visiteurs uniques pour hier">📈 Hier :
          <?php @readfile("Stats/visiteurs_jour_" . date('d', strtotime("-1 day")) . ".txt"); ?>&nbsp;
        </option>
        <option value="#FormSelectAutres" title="Visites" aria-label="Visites">📈 Visites :
          <?php @readfile("Stats/visites_jour_" . date('d') . ".txt"); ?>&nbsp;
        </option>
        <option value="#FormSelectAutres" title="Visites pour hier" aria-label="Visites pour hier">📈 Hier :
          <?php @readfile("Stats/visites_jour_" . date('d', strtotime("-1 day")) . ".txt"); ?>&nbsp;
        </option>
        <option value="https://github.com/Jean-Mich-Much/iActu.info" title="Version" aria-label="Version 15">📜 Version 15&nbsp;</option>
        <option value="https://github.com/Jean-Mich-Much/iActu.info" title="Lien GitHub" aria-label="GitHub">😸Lien code source&nbsp;</option>
        <option value="https://github.com/Jean-Mich-Much/iActu.info" title="Copyright" aria-label="© iactu.info 2003">📰 ©️ iactu.info 2003&nbsp;</option>
      </select>
    </form>
  </span>

  <span id="coucou" class="coucou" data-user="33imej" data-website="moc.liamg" title="Coucou 😊" aria-label="Coucou 😊" role="term"></span>

</nav>