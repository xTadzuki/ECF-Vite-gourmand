<?php
// app/Views/menus/show.php

require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Views/layouts/header.php';

// Variables attendues : $menu (array|null), $images (array), $dishes (array)
if (!isset($menu) || !$menu): ?>
  <div class="hero mb-4">
    <h1 class="mb-1">Menu introuvable</h1>
    <p class="text-muted mb-0">Ce menu n’existe pas ou a été supprimé.</p>
  </div>
  <a class="btn btn-dark" href="?r=menus">← Retour aux menus</a>
  <?php
    require_once BASE_PATH . '/app/Views/layouts/footer.php';
    exit;
  ?>
<?php endif; ?>

<?php
$stock    = (int)($menu['stock'] ?? 0);
$price    = (float)($menu['price'] ?? 0);
$maxPrice = isset($menu['max_price']) && $menu['max_price'] !== null ? (float)$menu['max_price'] : null;
$minPeople = (int)($menu['min_people'] ?? 0);

function stock_badge_show(int $stock): array {
  if ($stock <= 0) return ['badge-stock-out', 'Rupture'];
  if ($stock <= 3) return ['badge-stock-low', 'Stock faible'];
  return ['badge-stock-ok', 'En stock'];
}

[$stockClass, $stockLabel] = stock_badge_show($stock);

$images = $images ?? [];
$dishes = $dishes ?? [];
?>

<div class="hero mb-4">
  <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
    <div style="min-width: 260px; flex: 1;">
      <div class="d-flex gap-2 flex-wrap mb-2">
        <?php if (!empty($menu['theme'])): ?>
          <span class="badge-soft">Thème : <?= htmlspecialchars($menu['theme']) ?></span>
        <?php endif; ?>

        <?php if (!empty($menu['diet'])): ?>
          <span class="badge-soft">Régime : <?= htmlspecialchars($menu['diet']) ?></span>
        <?php endif; ?>

        <span class="badge-soft">Min : <?= $minPeople ?> pers.</span>
        <span class="<?= $stockClass ?>"><?= htmlspecialchars($stockLabel) ?></span>
      </div>

      <h1 class="mb-1"><?= htmlspecialchars($menu['title'] ?? 'Menu') ?></h1>
      <p class="text-muted mb-0">
        <?= nl2br(htmlspecialchars($menu['description'] ?? '')) ?>
      </p>
    </div>

    <div class="card" style="min-width: 280px;">
      <div class="card-body">
        <div class="text-muted small">À partir de</div>

        <div style="font-weight:900; font-size: 22px;">
          <?= number_format($price, 2, ',', ' ') ?> €
          <span class="text-muted" style="font-weight:600; font-size: 14px;">/ pers.</span>
        </div>

        <?php if ($maxPrice !== null): ?>
          <div class="text-muted small mt-1">
            Option premium jusqu’à <strong><?= number_format($maxPrice, 2, ',', ' ') ?> €</strong>/pers.
          </div>
        <?php endif; ?>

        <hr style="border-color: rgba(31,26,23,.10);">

        <?php if ($stock <= 0): ?>
          <div class="alert alert-warning mb-3">
            Ce menu est temporairement indisponible.
          </div>
          <a class="btn btn-dark w-100" href="?r=menus">Retour</a>
        <?php else: ?>
          <!-- Si ta route est order_create dans ton router -->
          <a class="btn btn-primary w-100"
   href="<?= htmlspecialchars($_SERVER['SCRIPT_NAME']) ?>?r=order_create&menu_id=<?= (int)($menu['id'] ?? 0) ?>">
  Commander ce menu
</a>

          <div class="text-muted small mt-2">
            Stock restant : <strong><?= $stock ?></strong>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Galerie images -->
<?php if (!empty($images)): ?>
  <div class="card mb-4">
    <div class="card-body">
      <h4 class="mb-3">Photos</h4>
      <div class="row g-3">
        <?php foreach ($images as $img): ?>
          <div class="col-12 col-sm-6 col-lg-4">
            <div class="card h-100">
              <img
                src="<?= htmlspecialchars($img['image_path'] ?? '') ?>"
                alt="<?= htmlspecialchars($img['alt_text'] ?? 'Image menu') ?>"
                class="rounded-4"
                style="width:100%; height:220px; object-fit:cover;"
              >
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
<?php endif; ?>

<!-- Plats + allergènes -->
<div class="card mb-4">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
      <h4 class="mb-0">Composition du menu</h4>
      <a class="btn btn-dark btn-sm" href="?r=menus">← Retour aux menus</a>
    </div>

    <?php if (empty($dishes)): ?>
      <div class="alert alert-info mb-0">
        Aucun plat n’est associé à ce menu (données de démo à compléter).
      </div>
    <?php else: ?>
      <?php
      // Groupement entrée / plat / dessert
      $groups = ['entrée' => [], 'plat' => [], 'dessert' => []];
      foreach ($dishes as $dish) {
        $type = $dish['type'] ?? 'plat';
        if (!isset($groups[$type])) $groups[$type] = [];
        $groups[$type][] = $dish;
      }
      ?>

      <?php foreach ($groups as $type => $items): ?>
        <?php if (empty($items)) continue; ?>

        <h5 class="mt-3 mb-2 text-uppercase" style="letter-spacing:.08em; font-weight:900;">
          <?= htmlspecialchars($type) ?>
        </h5>

        <div class="row g-3">
          <?php foreach ($items as $dish): ?>
            <div class="col-12 col-md-6">
              <div class="card h-100">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-start gap-2">
                    <div>
                      <div style="font-weight:850;"><?= htmlspecialchars($dish['name'] ?? '') ?></div>
                      <div class="text-muted small">Plat associé au menu</div>
                    </div>
                    <span class="badge-soft"><?= htmlspecialchars($type) ?></span>
                  </div>

                  <?php if (!empty($dish['allergens'])): ?>
                    <div class="mt-3 d-flex flex-wrap gap-2">
                      <?php foreach ($dish['allergens'] as $allergen): ?>
                        <span class="badge-soft">⚠ <?= htmlspecialchars($allergen) ?></span>
                      <?php endforeach; ?>
                    </div>
                  <?php else: ?>
                    <div class="text-muted small mt-3">Aucun allergène renseigné.</div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>
