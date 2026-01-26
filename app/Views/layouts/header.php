<?php
// app/Views/layouts/header.php

require_once BASE_PATH . '/app/Core/Route.php';

$role = $_SESSION['role'] ?? null;      // 'user' | 'employee' | 'admin'
$userId = $_SESSION['user_id'] ?? null;

function nav_active(string $route): string {
  $current = $_GET['r'] ?? Route::HOME;
  return $current === $route ? 'active' : '';
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Vite & Gourmand — Traiteur</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="?r=<?= Route::HOME ?>" aria-label="Accueil Vite & Gourmand">
        <span class="brand-pill">VG</span>
        <span>
          <span class="brand-title">Vite & Gourmand</span>
          <span class="text-muted brand-subtitle">Traiteur événementiel • Bordeaux</span>
        </span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
              aria-controls="mainNav" aria-expanded="false" aria-label="Ouvrir le menu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">

          <li class="nav-item">
            <a class="nav-link <?= nav_active(Route::HOME) ?>" href="?r=<?= Route::HOME ?>">Accueil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= nav_active(Route::MENUS) ?>" href="?r=<?= Route::MENUS ?>">Menus</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= nav_active(Route::CONTACT) ?>" href="?r=<?= Route::CONTACT ?>">Contact</a>
          </li>

          <?php if (!$userId): ?>
            <li class="nav-item">
              <a class="nav-link <?= nav_active(Route::REGISTER) ?>" href="?r=<?= Route::REGISTER ?>">Inscription</a>
            </li>
            <li class="nav-item ms-lg-2">
              <a class="btn btn-dark" href="?r=<?= Route::LOGIN ?>">Connexion</a>
            </li>
          <?php else: ?>

            <?php if ($role === 'user'): ?>
              <li class="nav-item">
                <a class="nav-link <?= nav_active(Route::USER_ORDERS) ?>" href="?r=<?= Route::USER_ORDERS ?>">Mes commandes</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= nav_active(Route::USER_PROFILE) ?>" href="?r=<?= Route::USER_PROFILE ?>">Mon profil</a>
              </li>
            <?php elseif ($role === 'employee'): ?>
              <li class="nav-item">
                <a class="nav-link <?= nav_active(Route::EMPLOYEE) ?>" href="?r=<?= Route::EMPLOYEE ?>">Espace employé</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= nav_active(Route::EMPLOYEE_REVIEWS) ?>" href="?r=<?= Route::EMPLOYEE_REVIEWS ?>">Avis</a>
              </li>
            <?php elseif ($role === 'admin'): ?>
              <li class="nav-item">
                <a class="nav-link <?= nav_active(Route::ADMIN) ?>" href="?r=<?= Route::ADMIN ?>">Admin</a>
              </li>
            <?php endif; ?>

            <li class="nav-item ms-lg-2">
              <a class="btn btn-outline-secondary" href="?r=<?= Route::LOGOUT ?>">Déconnexion</a>
            </li>
          <?php endif; ?>

        </ul>
      </div>
    </div>
  </nav>

  <?php if (($_GET['r'] ?? Route::HOME) === Route::HOME): ?>
    <header class="container mt-4 mb-4">
      <div class="hero">
        <div class="row align-items-center g-4">
          <div class="col-12 col-lg-7">
            <div class="d-flex flex-wrap gap-2 mb-2">
              <span class="badge badge-soft">✅ Devis rapide</span>
              <span class="badge badge-soft">🍽️ Menus de saison</span>
              <span class="badge badge-soft">🚚 Livraison</span>
            </div>

            <h1 class="mb-2">
              Un traiteur élégant, <span class="hero-accent-burgundy">gourmand</span> et <span class="hero-accent-sage">responsable</span>.
            </h1>

            <p class="mb-3">
              Mariages, anniversaires, entreprises… Choisis ton menu, adapte selon les régimes,
              et passe commande simplement.
            </p>

            <div class="d-flex flex-wrap gap-2">
              <a class="btn btn-primary" href="?r=<?= Route::MENUS ?>">Découvrir les menus</a>
              <a class="btn btn-dark" href="?r=<?= Route::CONTACT ?>">Demander un devis</a>
            </div>

            <div class="text-muted small mt-3">
              ⭐ Note moyenne 4,8 • 👩‍🍳 Cuisine maison • 📍 Bordeaux & alentours
            </div>
          </div>

          <div class="col-12 col-lg-5">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start gap-2">
                  <div>
                    <div class="text-muted small">Menu de saison</div>
                    <div style="font-weight:950; font-size:18px;">Menu de Noël – Tradition & Élégance</div>
                    <div class="text-muted small">À partir de <strong>39,90€</strong>/pers.</div>
                  </div>
                  <span class="badge badge-soft">🎄 Noël</span>
                </div>

                <hr style="border-color: rgba(31,26,23,.10);">

                <ul class="mb-3" style="padding-left: 1.1rem;">
                  <li>Foie gras mi-cuit & chutney</li>
                  <li>Volaille aux morilles</li>
                  <li>Bûche chocolat-noisette</li>
                </ul>

                <?php
$heroMenuId = isset($featuredMenus[0]['id']) ? (int)$featuredMenus[0]['id'] : 0;
?>

<?php if ($heroMenuId > 0): ?>
  <a class="btn btn-outline-secondary w-100" href="?r=menu_show&id=<?= $heroMenuId ?>">Voir le détail</a>
<?php else: ?>
  <a class="btn btn-outline-secondary w-100" href="?r=menus">Voir les menus</a>
<?php endif; ?>


            
              </div>
            </div>
          </div>

        </div>
      </div>
    </header>
  <?php endif; ?>

  <main class="container mb-5">
