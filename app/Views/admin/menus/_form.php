<?php
// app/Views/admin/menus/_form.php
require_once BASE_PATH . '/app/Core/Route.php';

$base = htmlspecialchars($_SERVER['SCRIPT_NAME']); // ex: /vite-gourmand/public/index.php

$menu   = $menu ?? [];
$themes = $themes ?? [];
$diets  = $diets ?? [];

$action = $action ?? ($base . '?r=' . Route::ADMIN_MENU_STORE);
$isEdit = !empty($isEdit);

$title = (string)($menu['title'] ?? '');
$desc  = (string)($menu['description'] ?? '');
$min   = (int)($menu['min_people'] ?? 1);
$price = (float)($menu['price'] ?? 0);
$stock = (int)($menu['stock'] ?? 0);
$themeId = $menu['theme_id'] ?? null;
$dietId  = $menu['diet_id'] ?? null;

?>
<link rel="stylesheet" href="assets/css/admin.css">

<form method="post" action="<?= $action ?>" class="row g-3">
  <?php if ($isEdit): ?>
    <input type="hidden" name="id" value="<?= (int)($menu['id'] ?? 0) ?>">
  <?php endif; ?>

  <div class="col-12 col-lg-6">
    <label class="form-label" for="title">Titre</label>
    <input class="form-control" id="title" name="title" required maxlength="190" value="<?= htmlspecialchars($title) ?>">
  </div>

  <div class="col-6 col-lg-2">
    <label class="form-label" for="min_people">Min personnes</label>
    <input class="form-control" id="min_people" name="min_people" type="number" min="1" step="1" value="<?= (int)$min ?>">
  </div>

  <div class="col-6 col-lg-2">
    <label class="form-label" for="price">Prix (€)</label>
    <input class="form-control" id="price" name="price" type="number" min="0" step="0.01" value="<?= htmlspecialchars((string)$price) ?>">
  </div>

  <div class="col-6 col-lg-2">
    <label class="form-label" for="stock">Stock</label>
    <input class="form-control" id="stock" name="stock" type="number" min="0" step="1" value="<?= (int)$stock ?>">
  </div>

  <div class="col-12 col-lg-6">
    <label class="form-label" for="theme_id">Thème</label>
    <select class="form-select" id="theme_id" name="theme_id">
      <option value="">—</option>
      <?php foreach ($themes as $t): ?>
        <option value="<?= (int)$t['id'] ?>" <?= ((string)$themeId === (string)$t['id']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($t['name'] ?? '') ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="col-12 col-lg-6">
    <label class="form-label" for="diet_id">Régime</label>
    <select class="form-select" id="diet_id" name="diet_id">
      <option value="">—</option>
      <?php foreach ($diets as $d): ?>
        <option value="<?= (int)$d['id'] ?>" <?= ((string)$dietId === (string)$d['id']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($d['name'] ?? '') ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="col-12">
    <label class="form-label" for="description">Description</label>
    <textarea class="form-control" id="description" name="description" rows="6"><?= htmlspecialchars($desc) ?></textarea>
  </div>

  <div class="col-12 d-flex gap-2">
    <button class="btn btn-dark" type="submit"><?= $isEdit ? 'Enregistrer' : 'Créer' ?></button>
    <a class="btn btn-outline-secondary" href="<?= $base ?>?r=<?= Route::ADMIN_MENUS ?>">Annuler</a>
  </div>
</form>
