<?php 
require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Views/layouts/header.php';
 ?>

<h1 class="mb-3">Conditions Générales de Vente (CGV)</h1>

<div class="card">
  <div class="card-body">
    <h5>Commande</h5>
    <p>Une commande est validée après confirmation. Certaines prestations nécessitent une anticipation (voir conditions du menu).</p>

    <h5>Livraison</h5>
    <p>Livraison gratuite à Bordeaux. Hors Bordeaux : 5€ + 0,59€/km.</p>

    <h5>Matériel prêté</h5>
    <p>En cas de prêt de matériel, restitution sous 10 jours ouvrés après passage au statut dédié. En cas de non-restitution : 600€ de frais.</p>

    <h5>Annulation</h5>
    <p>Le client peut annuler tant que la commande n’est pas “acceptée”. Au-delà, contacter l’entreprise.</p>
  </div>
</div>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php';
 ?>
