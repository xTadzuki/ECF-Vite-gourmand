<?php
// app/Views/employee/dashboard.php

require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Core/OrderStatus.php';

require_once BASE_PATH . '/app/Views/layouts/header.php';


$selectedStatus = $_GET['status'] ?? '';
$searchEmail    = $_GET['email'] ?? '';
?>

<div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
  <div>
    <h1 class="mb-1">Espace Employé</h1>
    <div class="text-muted">Gestion des commandes : filtres, statuts, annulation et notifications.</div>
  </div>
</div>

<!-- Filtres -->
<div class="card mb-3">
  <div class="card-body">
    <form method="get" class="row g-2 align-items-end">
      <input type="hidden" name="r" value="<?= Route::EMPLOYEE ?>">

      <div class="col-12 col-md-4">
        <label class="form-label">Email client</label>
        <input class="form-control" name="email" placeholder="ex: client@mail.com"
               value="<?= htmlspecialchars($searchEmail) ?>">
      </div>

      <div class="col-12 col-md-4">
        <label class="form-label">Statut</label>
        <select class="form-select" name="status">
          <option value="">Tous statuts</option>
          <?php foreach (OrderStatus::all() as $status): ?>
            <option value="<?= htmlspecialchars($status) ?>" <?= ($status === $selectedStatus) ? 'selected' : '' ?>>
              <?= htmlspecialchars($status) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-12 col-md-4 d-flex gap-2">
        <button class="btn btn-dark flex-grow-1">Filtrer</button>
        <a class="btn btn-outline-secondary flex-grow-1" href="?r=<?= Route::EMPLOYEE ?>">Réinitialiser</a>
      </div>
    </form>
  </div>
</div>

<!-- Liste -->
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <div style="font-weight:950;">Commandes</div>
      <div class="text-muted small"><?= count($orders ?? []) ?> résultat(s)</div>
    </div>

    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Menu</th>
            <th>Client</th>
            <th>Date évènement</th>
            <th>Total</th>
            <th>Statut</th>
            <th style="width: 280px;">Actions</th>
          </tr>
        </thead>
        <tbody>

        <?php if (empty($orders)): ?>
          <tr>
            <td colspan="7" class="text-muted">Aucune commande trouvée.</td>
          </tr>
        <?php else: ?>

          <?php foreach ($orders as $order): ?>
            <?php
              $orderId   = (int)($order['id'] ?? 0);
              $menuTitle = $order['menu_title'] ?? '';
              $userEmail = $order['user_email'] ?? '';
              $userName  = $order['user_name'] ?? '';
              $eventDate = $order['event_date'] ?? '';
              $total     = (float)($order['total_price'] ?? 0);
              $status    = $order['status'] ?? '';
              $cancelId  = 'cancelRow' . $orderId;
            ?>

            <tr>
              <td><?= $orderId ?></td>

              <td>
                <div style="font-weight:900;"><?= htmlspecialchars($menuTitle) ?></div>
                <div class="text-muted small">Commande #<?= $orderId ?></div>
              </td>

              <td>
                <div style="font-weight:700;"><?= htmlspecialchars(trim($userName) ?: 'Client') ?></div>
                <div class="text-muted small"><?= htmlspecialchars($userEmail) ?></div>
              </td>

              <td><?= htmlspecialchars($eventDate) ?></td>

              <td><?= number_format($total, 2, ',', ' ') ?> €</td>

              <td>
                <span class="badge badge-soft"><?= htmlspecialchars($status) ?></span>
              </td>

              <td>
                <!-- Changer statut -->
                <form method="post" action="?r=<?= Route::EMPLOYEE_UPDATE ?>" class="d-inline-flex flex-wrap gap-2">
                  <input type="hidden" name="order_id" value="<?= $orderId ?>">
                  <input type="hidden" name="email" value="<?= htmlspecialchars($userEmail) ?>">

                  <select name="status" class="form-select form-select-sm" style="min-width: 180px;">
                    <?php foreach (OrderStatus::all() as $s): ?>
                      <?php if ($s === OrderStatus::CREATED) continue; // on évite de revenir en "créée" ?>
                      <option value="<?= htmlspecialchars($s) ?>" <?= ($s === $status) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($s) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>

                  <button class="btn btn-sm btn-dark">OK</button>
                </form>

                <!-- Annuler -->
                <button class="btn btn-sm btn-outline-danger mt-2"
                        type="button"
                        onclick="
                          const row = document.getElementById('<?= $cancelId ?>');
                          row.style.display = (row.style.display === 'none' || row.style.display === '') ? 'table-row' : 'none';
                        ">
                  Annuler
                </button>
              </td>
            </tr>

            <!-- Row annulation -->
            <tr id="<?= $cancelId ?>" style="display:none;">
              <td colspan="7">
                <div class="card" style="background: var(--surface-2);">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <div style="font-weight:950;">Annulation — commande #<?= $orderId ?></div>
                      <button type="button" class="btn btn-sm btn-outline-secondary"
                              onclick="document.getElementById('<?= $cancelId ?>').style.display='none'">
                        Fermer
                      </button>
                    </div>

                    <form method="post" action="?r=<?= Route::EMPLOYEE_CANCEL ?>" class="row g-2">
                      <input type="hidden" name="order_id" value="<?= $orderId ?>">

                      <div class="col-12 col-md-3">
                        <label class="form-label">Mode de contact</label>
                        <select name="contact_mode" class="form-select" required>
                          <option value="">Choisir…</option>
                          <option value="email">Email</option>
                          <option value="gsm">GSM</option>
                        </select>
                      </div>

                      <div class="col-12 col-md-6">
                        <label class="form-label">Motif d'annulation</label>
                        <input class="form-control" name="reason" placeholder="ex: indisponibilité produit, délai, etc." required>
                      </div>

                      <div class="col-12 col-md-3 d-flex align-items-end">
                        <button class="btn btn-danger w-100">Confirmer annulation</button>
                      </div>
                    </form>

                    <div class="text-muted small mt-2">
                      L'annulation passe la commande en <strong><?= htmlspecialchars(OrderStatus::CANCELLED) ?></strong> et enregistre l'action dans l'historique.
                    </div>
                  </div>
                </div>
              </td>
            </tr>

          <?php endforeach; ?>

        <?php endif; ?>

        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php';
 ?>
