<?php
// app/Views/user/order_show.php
require_once BASE_PATH . '/app/Views/layouts/header.php';

// Sécurité affichage
$order = $order ?? [];
$histories = $history ?? []; // on renomme pour éviter collisions
$canEdit = $canEdit ?? false;
?>

<h1 class="mb-2">Commande #<?= (int)($order['id'] ?? 0) ?></h1>
<p class="text-muted mb-3">
  Menu : <strong><?= htmlspecialchars($order['menu_title'] ?? '') ?></strong>
</p>

<div class="row g-3">
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <h5>Détails</h5>

        <p class="mb-1">
          <strong>Ville :</strong>
          <?= htmlspecialchars($order['delivery_city'] ?? '—') ?>
          <?php if (!empty($order['delivery_distance'])): ?>
            <span class="text-muted small">(<?= htmlspecialchars((string)$order['delivery_distance']) ?> km)</span>
          <?php endif; ?>
        </p>

        <p class="mb-1">
          <strong>Date / heure :</strong>
          <?= htmlspecialchars($order['event_date'] ?? '') ?>
          <?= htmlspecialchars($order['event_time'] ?? '') ?>
        </p>

        <p class="mb-1"><strong>Personnes :</strong> <?= (int)($order['people_count'] ?? 0) ?></p>

        <p class="mb-1">
          <strong>Livraison :</strong>
          <?= number_format((float)($order['delivery_price'] ?? 0), 2, ',', ' ') ?> €
        </p>

        <?php if (!empty($order['discount']) && (float)$order['discount'] > 0): ?>
          <p class="mb-1">
            <strong>Remise :</strong>
            -<?= number_format((float)$order['discount'], 2, ',', ' ') ?> €
          </p>
        <?php endif; ?>

        <p class="mb-0">
          <strong>Total :</strong>
          <?= number_format((float)($order['total_price'] ?? 0), 2, ',', ' ') ?> €
        </p>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <h5>Suivi</h5>

        <?php if (empty($histories)): ?>
          <p class="text-muted mb-0">Aucun historique de statut.</p>
        <?php else: ?>
          <ul class="list-unstyled mb-0">
            <?php foreach ($histories as $h): ?>
              <li class="mb-1">
                <strong><?= htmlspecialchars($h['status'] ?? '') ?></strong>
                <span class="text-muted small">— <?= htmlspecialchars($h['changed_at'] ?? '') ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>

<div class="d-flex flex-wrap gap-2 mt-4">
  <a class="btn btn-outline-dark" href="?r=user_orders">← Mes commandes</a>

  <?php if ($canEdit): ?>
    <a class="btn btn-dark" href="?r=user_order_edit&id=<?= (int)($order['id'] ?? 0) ?>">Modifier</a>
    <a class="btn btn-outline-danger"
       href="?r=user_order_cancel&id=<?= (int)($order['id'] ?? 0) ?>"
       onclick="return confirm('Annuler cette commande ?');">Annuler</a>
  <?php else: ?>
    <span class="text-muted align-self-center">Commande non modifiable (déjà acceptée).</span>
  <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>
