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

<div class="hero mb-4">
  <div class="d-flex flex-wrap justify-content-between align-items-end gap-2">
    <div>
      <h1 class="mb-1">Tous les menus</h1>
      <p class="text-muted mb-0">Filtre en temps réel, consulte les détails et commande en ligne.</p>
    </div>
    <div class="text-muted small">
      <span id="menusCount"></span>
    </div>
  </div>
</div>

<!-- Bloc filtres -->
<div class="card mb-4">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
      <h4 class="mb-0">Filtres</h4>
      <span class="text-muted small">Combine les filtres pour affiner</span>
    </div>

    <form id="filtersForm" class="row g-3" aria-label="Filtres des menus">
      <div class="col-md-3">
        <label class="form-label" for="price_max">Prix max</label>
        <input class="form-control" type="number" step="0.01" id="price_max" name="price_max" placeholder="ex: 250">
      </div>

      <div class="col-md-3">
        <label class="form-label" for="price_min">Prix min</label>
        <input class="form-control" type="number" step="0.01" id="price_min" name="price_min" placeholder="ex: 100">
      </div>

      <div class="col-md-3">
        <label class="form-label" for="price_max_range">Prix max (fourchette)</label>
        <input class="form-control" type="number" step="0.01" id="price_max_range" name="price_max_range" placeholder="ex: 400">
      </div>

      <div class="col-md-3">
        <label class="form-label" for="min_people">Nb personnes minimum</label>
        <input class="form-control" type="number" id="min_people" name="min_people" placeholder="ex: 10">
      </div>

      <div class="col-md-6">
        <label class="form-label" for="theme_id">Thème</label>
        <select class="form-select" id="theme_id" name="theme_id">
          <option value="">Tous</option>
          <option value="1">Noël</option>
          <option value="2">Pâques</option>
          <option value="3">Classique</option>
        </select>
      </div>

      <div class="col-md-6">
        <label class="form-label" for="diet_id">Régime</label>
        <select class="form-select" id="diet_id" name="diet_id">
          <option value="">Tous</option>
          <option value="1">Classique</option>
          <option value="2">Végétarien</option>
          <option value="3">Vegan</option>
        </select>
      </div>

      <div class="col-12 d-flex flex-wrap gap-2">
        <button class="btn btn-primary" type="submit">Appliquer</button>
        <button class="btn btn-dark" type="button" id="resetFilters">Réinitialiser</button>
      </div>
    </form>
  </div>
</div>

<!-- Liste des menus -->
<div class="row g-3" id="menusList">
  <?php if (empty($menus)): ?>
    <div class="col-12">
      <p class="text-muted mb-0">Aucun menu disponible.</p>
    </div>
  <?php else: ?>
    <?php foreach ($menus as $menu): ?>
      <?php
        $id = (int)($menu['id'] ?? 0);
        $stock = (int)($menu['stock'] ?? 0);
        [$stockClass, $stockLabel] = stock_badge($stock);
      ?>

      <div class="col-12 col-md-6 col-lg-4">
        <div class="card h-100">
          <div class="card-body d-flex flex-column">

            <div class="menu-card-head d-flex justify-content-between align-items-center gap-2 mb-2">
              <h5 class="card-title mb-0"><?= htmlspecialchars($menu['title'] ?? '') ?></h5>
              <span class="<?= $stockClass ?>"><?= htmlspecialchars($stockLabel) ?></span>
            </div>

            <div class="d-flex flex-wrap gap-2 mb-3">
              <span class="badge-soft">Thème : <?= htmlspecialchars($menu['theme'] ?? '—') ?></span>
              <span class="badge-soft">Régime : <?= htmlspecialchars($menu['diet'] ?? '—') ?></span>
              <span class="badge-soft">Min : <?= (int)($menu['min_people'] ?? 0) ?> pers.</span>
            </div>

            <p class="text-muted mb-3" style="min-height:3.6em;">
              <?= nl2br(htmlspecialchars(mb_strimwidth($menu['description'] ?? '', 0, 140, '…'))) ?>
            </p>

            <div class="mt-auto d-flex justify-content-between align-items-center">
              <div>
                <div class="text-muted small">À partir de</div>
                <div style="font-weight:900; font-size: 18px;">
                  <?= number_format((float)($menu['price'] ?? 0), 2, ',', ' ') ?> €
                </div>
              </div>

              <?php if ($id > 0): ?>
                <a class="btn btn-outline-dark" href="?r=menu_show&id=<?= $id ?>">Détails</a>
              <?php else: ?>
                <span class="text-muted small">ID manquant</span>
              <?php endif; ?>
            </div>

            <?php if ($stock <= 0): ?>
              <div class="alert alert-warning mt-3 mb-0">
                Ce menu n’est plus disponible pour le moment.
              </div>
            <?php endif; ?>

          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>