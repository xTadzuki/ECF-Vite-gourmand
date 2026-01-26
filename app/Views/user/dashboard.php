<?php
// app/Views/user/dashboard.php
require_once BASE_PATH . '/app/Views/layouts/header.php';

$ordersList = $orders ?? [];

function status_badge_class(string $status): string
{
  $s = mb_strtolower(trim($status));

  return match ($s) {
    'créée' => 'bg-secondary',
    'accepté', 'acceptée' => 'bg-info',
    'en préparation' => 'bg-primary',
    'en cours de livraison', 'livré', 'livrée', 'terminée' => 'bg-success',
    'annulé', 'annulée' => 'bg-danger',
    default => 'bg-dark'
  };
}

function can_edit_user_order(string $status): bool
{
  return mb_strtolower(trim($status)) === 'créée';
}
?>

<h1 class="mb-3">Mon espace</h1>

<div class="d-flex flex-wrap gap-2 mb-3">
  <a class="btn btn-outline-dark" href="?r=user_profile">Mes informations</a>
  <a class="btn btn-dark" href="?r=user_orders">Mes commandes</a>
</div>

<h4 class="mb-3">Mes commandes</h4>

<?php if (empty($ordersList)): ?>
  <div class="alert alert-info mb-0">Aucune commande pour le moment.</div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Menu</th>
          <th>Prestation</th>
          <th>Ville</th>
          <th class="text-center">Pers.</th>
          <th class="text-end">Total</th>
          <th>Statut</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>

      <tbody>
      <?php foreach ($ordersList as $order): ?>
        <?php
          $id = (int)($order['id'] ?? 0);
          $status = (string)($order['status'] ?? '');
          $badgeClass = status_badge_class($status);
          $canEdit = can_edit_user_order($status);
        ?>

        <tr>
          <td><?= $id ?></td>

          <td>
            <div style="font-weight:700;">
              <?= htmlspecialchars($order['menu_title'] ?? '') ?>
            </div>
            <div class="text-muted small">
              Commande du <?= htmlspecialchars($order['created_at'] ?? '') ?>
            </div>
          </td>

          <td>
            <?= htmlspecialchars($order['event_date'] ?? '') ?>
            <?= htmlspecialchars($order['event_time'] ?? '') ?>
          </td>

          <td><?= htmlspecialchars($order['delivery_city'] ?? '—') ?></td>

          <td class="text-center"><?= (int)($order['people_count'] ?? 0) ?></td>

          <td class="text-end">
            <?= number_format((float)($order['total_price'] ?? 0), 2, ',', ' ') ?> €
          </td>

          <td>
            <span class="badge <?= $badgeClass ?>">
              <?= htmlspecialchars($status) ?>
            </span>
          </td>

          <td class="text-end">
            <a class="btn btn-sm btn-outline-dark"
               href="?r=user_order_show&id=<?= $id ?>">
              Détails
            </a>

            <?php if ($canEdit): ?>
              <a class="btn btn-sm btn-dark"
                 href="?r=user_order_edit&id=<?= $id ?>">
                Modifier
              </a>

              <a class="btn btn-sm btn-outline-danger"
                 href="?r=user_order_cancel&id=<?= $id ?>"
                 onclick="return confirm('Annuler cette commande ?');">
                Annuler
              </a>
            <?php endif; ?>
          </td>
        </tr>

      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="text-muted small mt-2">
    * Les commandes “créée” peuvent être modifiées/annulées. Une fois acceptées, elles ne sont plus modifiables.
  </div>
<?php endif; ?>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>
