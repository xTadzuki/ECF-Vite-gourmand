<?php
// app/Views/admin/menus/edit.php
require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Views/layouts/header.php';

$base = htmlspecialchars($_SERVER['SCRIPT_NAME']);
$action = $base . '?r=' . Route::ADMIN_MENU_UPDATE;

$isEdit = true;
?>
<link rel="stylesheet" href="assets/css/admin.css">

<div class="ad-wrap">
  <div class="ad-head">
    <div>
      <h1 class="ad-title">Éditer le menu</h1>
      <p class="ad-sub">Modifie les infos. Pense à vérifier le stock et le prix.</p>
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
