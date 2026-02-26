<?php
// app/Views/admin/menus/create.php
require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Views/layouts/header.php';

$base = htmlspecialchars($_SERVER['SCRIPT_NAME']);
$action = $base . '?r=' . Route::ADMIN_MENU_STORE;

$isEdit = false;
?>
<link rel="stylesheet" href="assets/css/admin.css">

<div class="ad-wrap">
  <div class="ad-head">
    <div>
      <h1 class="ad-title">Créer un menu</h1>
      <p class="ad-sub">Renseigne les informations principales. Les plats / images peuvent être gérés dans une itération suivante.</p>
    </div>
    <div class="ad-actions">
      <a class="ad-btn" href="<?= $base ?>?r=<?= Route::ADMIN_MENUS ?>">Retour</a>
    </div>
  </div>

  <div class="ad-card">
    <div class="ad-card__body">
      <?php require BASE_PATH . '/app/Views/admin/menus/_form.php'; ?>
    </div>
  </div>
</div>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>
