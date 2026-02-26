<?php
// app/Views/menus/show.php

require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Views/layouts/header.php';

// Variables attendues : $menu (array|null), $images (array), $dishes (array)
if (!isset($menu) || !$menu): ?>
  <section class="vg-pagehead">
    <div>
      <h1 class="vg-h1">Menu introuvable</h1>
      <p class="vg-muted mb-0">Ce menu n’existe pas ou a été supprimé.</p>
    </div>
    <a class="vg-btn vg-btn--ghost" href="?r=menus">← Retour aux menus</a>
  </section>
  <?php
    require_once BASE_PATH . '/app/Views/layouts/footer.php';
    exit;
  ?>
<?php endif; ?>

<?php
$stock     = (int)($menu['stock'] ?? 0);
$price     = (float)($menu['price'] ?? 0);
$maxPrice  = isset($menu['max_price']) && $menu['max_price'] !== null ? (float)$menu['max_price'] : null;
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

<section class="vg-pagehead">
  <div>
    <h1 class="vg-h1"><?= htmlspecialchars($menu['title'] ?? 'Menu') ?></h1>
    <p class="vg-muted mb-0"><?= nl2br(htmlspecialchars($menu['description'] ?? '')) ?></p>
  </div>
  <a class="vg-btn vg-btn--ghost" href="?r=menus">← Retour aux menus</a>
</section>

<div class="vg-menu-detail">

  <!-- Contenu -->
  <section class="vg-surface vg-panel">
    <div class="d-flex gap-2 flex-wrap mb-2">
      <?php if (!empty($menu['theme'])): ?>
        <span class="vg-badge"><?= htmlspecialchars($menu['theme']) ?></span>
      <?php endif; ?>

      <?php if (!empty($menu['diet'])): ?>
        <span class="vg-badge"><?= htmlspecialchars($menu['diet']) ?></span>
      <?php endif; ?>

      <span class="vg-badge">Min <?= $minPeople ?> pers.</span>
      <span class="<?= $stockClass ?>"><?= htmlspecialchars($stockLabel) ?></span>
    </div>

    <?php if (!empty($images)): ?>
      <div class="vg-gallery" aria-label="Galerie photos">
        <?php foreach ($images as $img): ?>
          <img
            class="vg-gallery__img"
            src="<?= htmlspecialchars($img['image_path'] ?? '') ?>"
            alt="<?= htmlspecialchars($img['alt_text'] ?? 'Image menu') ?>"
          >
        <?php endforeach; ?>
      </div>
      <div class="vg-divider"></div>
    <?php endif; ?>

    <h2 class="vg-h2">Composition du menu</h2>

    <?php if (empty($dishes)): ?>
      <p class="vg-muted mb-0">Aucun plat n’est associé à ce menu (données de démo à compléter).</p>
    <?php else: ?>
      <?php
      $groups = ['entrée' => [], 'plat' => [], 'dessert' => []];
      foreach ($dishes as $dish) {
        $type = $dish['type'] ?? 'plat';
        if (!isset($groups[$type])) $groups[$type] = [];
        $groups[$type][] = $dish;
      }
      ?>

      <?php foreach ($groups as $type => $items): ?>
        <?php if (empty($items)) continue; ?>

        <h3 class="vg-kicker" style="margin-top:14px;">
          <?= htmlspecialchars(strtoupper($type)) ?>
        </h3>

        <ul class="vg-list">
          <?php foreach ($items as $dish): ?>
            <li>
              <strong><?= htmlspecialchars($dish['name'] ?? '') ?></strong>
              <?php if (!empty($dish['allergens'])): ?>
                <div class="vg-muted" style="font-size:13px; margin-top:4px;">
                  Allergènes : <?= htmlspecialchars(implode(', ', $dish['allergens'])) ?>
                </div>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>

  <!-- Commander -->
  <aside class="vg-surface vg-panel vg-order">
    <h2 class="vg-h2">Commander</h2>
    <div class="vg-divider"></div>

    <div class="vg-muted small">Prix par personne</div>
    <div class="vg-order__price"><?= number_format($price, 2, ',', ' ') ?> €</div>

    <?php if ($maxPrice !== null): ?>
      <div class="vg-muted" style="font-size:13px; margin-top:6px;">
        Option premium jusqu’à <strong><?= number_format($maxPrice, 2, ',', ' ') ?> €</strong>/pers.
      </div>
    <?php endif; ?>

    <div class="vg-divider"></div>

    <?php if ($stock <= 0): ?>
      <div class="alert alert-warning mb-3">Ce menu est temporairement indisponible.</div>
      <a class="vg-btn vg-btn--primary" href="?r=menus" style="width:100%; height:44px;">Retour aux menus</a>
    <?php else: ?>
      <a class="vg-btn vg-btn--primary" href="?r=order_create&menu_id=<?= (int)($menu['id'] ?? 0) ?>" style="width:100%; height:44px;">
        Valider
      </a>
      <div class="vg-muted" style="font-size:13px; margin-top:10px;">Stock restant : <strong><?= $stock ?></strong></div>
    <?php endif; ?>
  </aside>

</div>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>
