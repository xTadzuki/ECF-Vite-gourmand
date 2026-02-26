<?php
// app/Views/layouts/header.php

require_once BASE_PATH . '/app/Core/Route.php';

// Base URL (ex: /vite-gourmand/public) 
$base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if ($base === '') $base = '';

$role = $_SESSION['role'] ?? null;      // 'user' | 'employee' | 'admin'
$userId = $_SESSION['user_id'] ?? null;

function nav_active(string $route): string
{
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
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Allura&family=Playfair+Display:wght@500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= htmlspecialchars($base) ?>/assets/css/style.css?v=1">
  <link rel="stylesheet" href="<?= htmlspecialchars($base) ?>/assets/css/vg-theme.css?v=1">
</head>

<body>
  <nav class="navbar navbar-expand-lg sticky-top vg-navbar">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="?r=<?= Route::HOME ?>" aria-label="Accueil Vite & Gourmand">
        <span class="vg-brand">
          <span class="vg-brand__name">Vite &amp; Gourmand</span>
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
              <a class="vg-btn vg-btn--primary" href="?r=<?= Route::LOGIN ?>">Connexion</a>
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
              <a class="vg-btn vg-btn--ghost" href="?r=<?= Route::LOGOUT ?>">Déconnexion</a>
            </li>
          <?php endif; ?>

        </ul>
      </div>
    </div>
  </nav>

  <main class="vg-container mb-5">
