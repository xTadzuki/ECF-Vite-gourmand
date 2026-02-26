<?php
// app/Views/admin/dashboard.php
require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Views/layouts/header.php';

$employeesList = $employees ?? [];
$stats = $stats ?? [];

$base = htmlspecialchars($_SERVER['SCRIPT_NAME']); // ex: /vite-gourmand/public/index.php
?>

<h1 class="mb-3">Espace Administrateur</h1>

<div class="d-flex flex-wrap gap-2 mb-4">
  <a class="btn btn-dark" href="<?= $base ?>?r=<?= Route::ADMIN_MENUS ?>">Gérer les menus</a>
  <a class="btn btn-outline-dark" href="<?= $base ?>?r=<?= Route::ADMIN ?>">Dashboard</a>
</div>

<div class="card mb-4">
  <div class="card-body">
    <h4 class="mb-3">Créer un employé</h4>

    <form method="post" action="<?= $base ?>?r=<?= Route::ADMIN_CREATE ?>" class="row g-2">
      <div class="col-md-4">
        <input class="form-control" name="email" type="email" placeholder="Email employé" required>
      </div>

      <div class="col-md-4">
        <input class="form-control" name="password" type="password" placeholder="Mot de passe" required>
      </div>

      <div class="col-md-4">
        <button class="btn btn-dark w-100" type="submit">Créer</button>
      </div>
    </form>
  </div>
</div>

<div class="card mb-4">
  <div class="card-body">
    <h4 class="mb-3">Employés</h4>

    <?php if (empty($employeesList)): ?>
      <div class="alert alert-info mb-0">Aucun employé pour le moment.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th>Email</th>
              <th>Statut</th>
              <th class="text-end">Action</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($employeesList as $employee): ?>
            <tr>
              <td><?= htmlspecialchars($employee['email'] ?? '') ?></td>
              <td>
                <?php if (!empty($employee['is_active'])): ?>
                  <span class="badge bg-success">Actif</span>
                <?php else: ?>
                  <span class="badge bg-secondary">Inactif</span>
                <?php endif; ?>
              </td>
              <td class="text-end">
                <form method="post" action="<?= $base ?>?r=<?= Route::ADMIN_TOGGLE ?>" class="d-inline">
                  <input type="hidden" name="id" value="<?= (int)($employee['id'] ?? 0) ?>">
                  <button class="btn btn-sm btn-outline-dark" type="submit">
                    Activer / Désactiver
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>


<div class="card mb-4">
  <div class="card-body">
    <h4 class="mb-3">Statistiques</h4>

    <div class="row g-3">
      <div class="col-12 col-lg-6">
        <div class="p-3 border rounded-3 bg-light-subtle">
          <div class="fw-semibold mb-2">Commandes par statut</div>
          <canvas id="chartOrdersByStatus" height="140"></canvas>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="p-3 border rounded-3 bg-light-subtle">
          <div class="fw-semibold mb-2">Chiffre d’affaires (12 mois)</div>
          <canvas id="chartRevenueByMonth" height="140"></canvas>
        </div>
      </div>

      <div class="col-12">
        <div class="p-3 border rounded-3 bg-light-subtle">
          <div class="fw-semibold mb-2">Top menus (par commandes)</div>
          <canvas id="chartTopMenus" height="120"></canvas>
        </div>
      </div>
    </div>

    <p class="text-muted small mt-3 mb-0" id="statsStatus">Chargement…</p>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="assets/js/admin-dashboard.js" defer></script>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>
