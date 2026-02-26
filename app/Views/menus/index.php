<?php
// app/Views/menus/index.php

require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Views/layouts/header.php';

function stock_badge(int $stock): array {
  if ($stock <= 0) return ['badge-stock-out', 'Rupture'];
  if ($stock <= 3) return ['badge-stock-low', 'Stock faible'];
  return ['badge-stock-ok', 'En stock'];
}
?>

<section class="vg-pagehead">
  <div>
    <h1 class="vg-h1">Menus</h1>
    <p class="vg-muted mb-0">Filtre en temps réel, consulte les détails et commande en ligne.</p>
  </div>
  <div class="vg-muted small"><span id="menusCount"></span></div>
</section>

<div class="vg-menus-layout">

  <!-- Filtres (sidebar) -->
  <aside class="vg-surface vg-filters">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h2 class="vg-h2">Filtres</h2>
      <button id="filtersReset" class="vg-btn vg-btn--ghost" type="button">Réinitialiser</button>
    </div>

    <form id="filtersForm" aria-label="Filtres des menus">
      <div class="vg-field">
        <label for="theme_id">Thème</label>
        <select id="theme_id" name="theme_id">
          <option value="">Tous</option>
          <option value="1">Noël</option>
          <option value="2">Pâques</option>
          <option value="3">Classique</option>
        </select>
      </div>

      <div class="vg-field">
        <label for="diet_id">Régime</label>
        <select id="diet_id" name="diet_id">
          <option value="">Tous</option>
          <option value="1">Classique</option>
          <option value="2">Végétarien</option>
          <option value="3">Vegan</option>
        </select>
      </div>

      <div class="vg-field">
        <label for="min_people">Convives (min)</label>
        <input type="number" id="min_people" name="min_people" placeholder="ex: 10">
      </div>

      <div class="vg-field">
        <label for="price_min">Prix min</label>
        <input type="number" step="0.01" id="price_min" name="price_min" placeholder="ex: 100">
      </div>

      <div class="vg-field">
        <label for="price_max">Prix max</label>
        <input type="number" step="0.01" id="price_max" name="price_max" placeholder="ex: 250">
      </div>

      <div class="vg-field">
        <label for="price_max_range">Prix max (fourchette)</label>
        <input type="number" step="0.01" id="price_max_range" name="price_max_range" placeholder="ex: 400">
      </div>
    </form>
  </aside>

  <!-- Liste -->
  <section class="vg-surface vg-menus-panel">
    <div id="menusList" class="vg-menu-list">
      <?php if (empty($menus)): ?>
        <p class="vg-muted mb-0">Aucun menu disponible.</p>
      <?php else: ?>
        <?php foreach ($menus as $menu): ?>
          <?php
            $id = (int)($menu['id'] ?? 0);
            $stock = (int)($menu['stock'] ?? 0);
            $thumb = (string)($menu['thumb'] ?? '');
            [$stockClass, $stockLabel] = stock_badge($stock);
          ?>

          <article class="vg-menu-card">
            <div class="vg-menu-card__media">
              <?php if ($thumb !== ''): ?>
                <img class="vg-menu-card__img" src="<?= htmlspecialchars($thumb) ?>" alt="<?= htmlspecialchars($menu['title'] ?? 'Menu') ?>">
              <?php else: ?>
                <div class="vg-menu-card__ph" aria-hidden="true">🍽️</div>
              <?php endif; ?>
            </div>

            <div class="vg-menu-card__body">
              <div class="vg-menu-card__top">
                <h3 class="vg-menu-card__title"><?= htmlspecialchars($menu['title'] ?? '') ?></h3>
                <span class="<?= $stockClass ?>"><?= htmlspecialchars($stockLabel) ?></span>
              </div>

              <div class="vg-menu-card__meta">
                <span class="vg-badge"><?= htmlspecialchars($menu['theme'] ?? '—') ?></span>
                <span class="vg-badge"><?= htmlspecialchars($menu['diet'] ?? '—') ?></span>
                <span class="vg-badge">Min <?= (int)($menu['min_people'] ?? 0) ?> pers.</span>
              </div>

              <p class="vg-menu-card__desc"><?= htmlspecialchars(mb_strimwidth((string)($menu['description'] ?? ''), 0, 160, '…')) ?></p>
            </div>

            <div class="vg-menu-card__cta">
              <div class="vg-price">
                <div class="vg-muted small">À partir de</div>
                <div class="vg-price__value"><?= number_format((float)($menu['price'] ?? 0), 2, ',', ' ') ?> €</div>
              </div>

              <?php if ($id > 0): ?>
                <a class="vg-btn vg-btn--primary" href="?r=menu_show&id=<?= $id ?>">Voir détails</a>
              <?php else: ?>
                <span class="vg-muted small">ID manquant</span>
              <?php endif; ?>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>

</div>

<script src="assets/js/menus-filters.js" defer></script>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>
