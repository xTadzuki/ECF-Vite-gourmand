<?php
// app/Views/admin/menus/index.php
require_once BASE_PATH . '/app/Core/Route.php';

$menus = $menus ?? [];

// Base URL robuste (Fly = /index.php)
$root = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if ($root === '') $root = '';
$index = $root . '/index.php';
?>

<link rel="stylesheet" href="<?= htmlspecialchars($root) ?>/assets/css/admin.css">

<div class="ad-wrap">
  <div class="ad-head">
    <div>
      <h1 class="ad-title">Admin — Menus</h1>
      <p class="ad-sub">Créer, modifier et supprimer les menus affichés sur le site.</p>
    </div>
    <div class="ad-actions">
      <a class="ad-btn ad-btn--primary" href="<?= htmlspecialchars($index) ?>?r=<?= Route::ADMIN_MENU_CREATE ?>">+ Nouveau menu</a>
      <a class="ad-btn" href="<?= htmlspecialchars($index) ?>?r=<?= Route::ADMIN ?>">Dashboard</a>
    </div>
  </div>

  <div class="ad-card">
    <div class="ad-card__body">
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th>Titre</th>
              <th>Thème</th>
              <th>Régime</th>
              <th>Min</th>
              <th>Prix</th>
              <th>Stock</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($menus)): ?>
              <tr><td colspan="7" class="text-muted">Aucun menu.</td></tr>
            <?php else: ?>
              <?php foreach ($menus as $m): ?>
                <tr>
                  <td><?= htmlspecialchars($m['title'] ?? '') ?></td>
                  <td><?= htmlspecialchars($m['theme'] ?? '—') ?></td>
                  <td><?= htmlspecialchars($m['diet'] ?? '—') ?></td>
                  <td><?= (int)($m['min_people'] ?? 0) ?></td>
                  <td><?= number_format((float)($m['price'] ?? 0), 2, ',', ' ') ?> €</td>
                  <td><?= (int)($m['stock'] ?? 0) ?></td>
                  <td class="text-end">
                    <a class="btn btn-sm btn-outline-dark"
                       href="<?= htmlspecialchars($index) ?>?r=<?= Route::ADMIN_MENU_EDIT ?>&id=<?= (int)($m['id'] ?? 0) ?>">
                      Éditer
                    </a>

                    <form class="d-inline" method="post"
                          action="<?= htmlspecialchars($index) ?>?r=<?= Route::ADMIN_MENU_DELETE ?>"
                          onsubmit="return confirm('Supprimer ce menu ?');">
                      <input type="hidden" name="id" value="<?= (int)($m['id'] ?? 0) ?>">
                      <button class="btn btn-sm btn-outline-danger" type="submit">Supprimer</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
