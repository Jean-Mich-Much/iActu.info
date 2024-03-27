<?php
$affiche_message_haut = false;
?>

<?php if ($affiche_message_haut) { ?>

  <aside class="messagehaut">

    <section>
      📋 Dons mars : manque 110€
    </section>

    <section>
      <a href="https://www.paypal.com/donate/?hosted_button_id=QVKRNFGMPXYXY" target="_blank" id="MessageHautFaireundon" aria-label="Faire un don, merci par avance 😊" alt="Faire un don, merci par avance 😊" title="Faire un don, merci par avance 😊">&nbsp;🎁Faire un don (manque 110€)&nbsp;</a>
    </section>

    <section>
      <form>
        <select aria-label="Liste des donateurs" id="MessageHautListeDonateurs" name="Liste des donateurs" size="1" title="Liste des donateurs">
          <option title="Liste des donateurs" aria-label="Liste des donateurs" selected>👤 Philippe D. 10€ - 25/03&nbsp;</option>
          <option title="Donateur" aria-label="Donateur">👤 Xavier M. 5€ - 05/03&nbsp;</option>
          <option title="Donateur" aria-label="Donateur">👤 Sebastien D. 5€ - 03/03&nbsp;</option>
        </select>
      </form>
    </section>

  </aside>

<?php } ?>