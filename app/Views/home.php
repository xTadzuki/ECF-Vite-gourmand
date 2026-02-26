<?php
// app/Views/home.php

require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Views/layouts/header.php';

// Base URL (ex: /vite-gourmand/public)
$base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if ($base === '') $base = '';

// Données fournies par HomeController
$featured = $featuredMenus ?? [];

// On réutilise les mêmes menus pour le carousel du bas si le contrôleur n'en fournit pas d'autres
$allMenus = $menus ?? $featured;

// Avis : HomeController renvoie potentiellement rating/comment/created_at
$reviewsList = $reviews ?? [];
if (!is_array($reviewsList) || count($reviewsList) === 0) {
  $reviewsList = [
    ['author' => 'Camille R.', 'content' => 'Service impeccable, plats délicieux et présentation superbe.', 'rating' => 5, 'date' => '2025-10-12'],
    ['author' => 'Jean M.',   'content' => 'Menus variés, équipe réactive, nos invités ont adoré.',       'rating' => 5, 'date' => '2025-09-28'],
    ['author' => 'Sarah L.',  'content' => 'Très pro, livraison à l’heure et qualité au rendez-vous.',   'rating' => 4, 'date' => '2025-09-02'],
    ['author' => 'Nicolas P.', 'content' => 'Très bon rapport qualité/prix. Portions généreuses.',        'rating' => 4, 'date' => '2025-08-20'],
    ['author' => 'Julie T.',  'content' => 'Présentation soignée, service discret et efficace.',         'rating' => 5, 'date' => '2025-08-01'],
    ['author' => 'Mehdi A.',  'content' => 'Organisation parfaite, menus délicieux pour notre événement.', 'rating' => 5, 'date' => '2025-07-16'],
    ['author' => 'Léa S.',    'content' => 'Options végétariennes excellentes, tout le monde a aimé.',   'rating' => 5, 'date' => '2025-07-03'],
    ['author' => 'Thomas G.', 'content' => 'Ponctuels, pros, et très bon suivi avant la commande.',      'rating' => 4, 'date' => '2025-06-19'],
  ];
}

// Normalise un chemin :
// - URL http(s) : inchangé
// - chemin commençant par "/" : préfixe avec $base
// - chemin relatif : préfixe avec $base + "/"
$asset = function (?string $path) use ($base): string {
  $p = trim((string)$path);
  if ($p === '') return '';
  if (str_starts_with($p, 'http://') || str_starts_with($p, 'https://')) return $p;
  if (str_starts_with($p, '/')) return $base . $p;
  return $base . '/' . $p;
};

$menuImg = function ($m) use ($asset): string {
  $p = $m['thumb'] ?? $m['image_path'] ?? null;
  return $p ? $asset($p) : $asset('/assets/img/placeholder-menu.jpg');
};

$heroImg = $asset('/assets/img/hero.jpg');
?>

<!-- HERO (maquette) -->
<section style="margin-top:18px; margin-bottom:18px;">
  <div class="vg-hero" style="--vg-hero-img: url('<?= htmlspecialchars($heroImg) ?>');">
    <div class="vg-hero__bg" aria-hidden="true"></div>
    <div class="vg-hero__overlay" aria-hidden="true"></div>

    <div class="vg-hero__content">
      <p class="vg-kicker">Traiteur événementiel</p>
      <h1 class="vg-h1" style="color:#fff; max-width:560px;">Des menus sur-mesure pour vos événements</h1>
      <p class="vg-hero__lead">Mariages, anniversaires, entreprises… Choisissez, filtrez, commandez simplement.</p>
      <a class="vg-btn vg-btn--primary" href="?r=<?= Route::MENUS ?>">Découvrir nos menus</a>
    </div>
  </div>
</section>

<!-- NOS MENUS À LA UNE (carousel) -->
<section class="vg-section">
  <h2 class="vg-section__title">Nos Menus à la Une</h2>

  <div class="vg-carousel" data-carousel="featured">
    <button class="vg-carousel__nav vg-carousel__nav--prev" type="button" aria-label="Précédent" data-prev>‹</button>

    <div class="vg-carousel__viewport" data-viewport>
      <div class="vg-carousel__track" data-track>
        <?php foreach ($featured as $m): ?>
          <article class="vg-card vg-card--menu">
            <img class="vg-card__img" src="<?= htmlspecialchars($menuImg($m)) ?>" alt="<?= htmlspecialchars($m['title'] ?? 'Menu') ?>">
            <div class="vg-card__body">
              <h3 class="vg-card__title"><?= htmlspecialchars($m['title'] ?? 'Menu') ?></h3>

              <div class="vg-card__meta">
                <?php if (!empty($m['theme'])): ?><span class="vg-badge"><?= htmlspecialchars($m['theme']) ?></span><?php endif; ?>
                <?php if (!empty($m['diet'])): ?><span class="vg-badge vg-badge--soft"><?= htmlspecialchars($m['diet']) ?></span><?php endif; ?>
              </div>

              <div class="vg-card__foot">
                <div class="vg-price"><?= isset($m['price']) ? htmlspecialchars((string)$m['price']) : '—' ?>€</div>
                <a class="vg-btn vg-btn--small vg-btn--primary" href="?r=<?= Route::MENU_SHOW ?>&id=<?= (int)($m['id'] ?? 0) ?>">Voir</a>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>

    <button class="vg-carousel__nav vg-carousel__nav--next" type="button" aria-label="Suivant" data-next>›</button>
  </div>
</section>

<!-- AVIS CLIENTS (carousel) -->
<section class="vg-section vg-section--soft">
  <h2 class="vg-section__title">Avis Clients</h2>
  <div class="vg-sep-dot" aria-hidden="true"></div>

  <div class="vg-carousel vg-carousel--quotes" data-carousel="reviews">
    <button class="vg-carousel__nav vg-carousel__nav--prev" type="button" aria-label="Précédent" data-prev>‹</button>

    <div class="vg-carousel__viewport" data-viewport>
      <div class="vg-carousel__track" data-track>
        <?php foreach ($reviewsList as $r): ?>
          <?php
          $content = $r['content'] ?? $r['comment'] ?? '';
          $author  = $r['author'] ?? 'Client';
          $rating  = (int)($r['rating'] ?? 5);
          $rating  = max(1, min(5, $rating));
          $date    = $r['date'] ?? ($r['created_at'] ?? null);
          // normalise date si besoin
          if (is_string($date) && strlen($date) >= 10) $date = substr($date, 0, 10);
          ?>
          <article class="vg-quote">
            <div class="vg-quote__top">
              <div class="vg-quote__icon">“</div>
              <div class="vg-quote__stars" aria-label="Note">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                  <?= $i <= $rating ? '★' : '☆' ?>
                <?php endfor; ?>
              </div>
            </div>

            <p class="vg-quote__text"><?= htmlspecialchars((string)$content) ?></p>

            <div class="vg-quote__foot">
              <p class="vg-quote__author"><?= htmlspecialchars((string)$author) ?></p>
              <?php if (!empty($date)): ?>
                <p class="vg-quote__date"><?= htmlspecialchars((string)$date) ?></p>
              <?php endif; ?>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>

    <button class="vg-carousel__nav vg-carousel__nav--next" type="button" aria-label="Suivant" data-next>›</button>
  </div>
</section>

<!-- NOS MENUS (carousel bas) -->
<section class="vg-section">
  <div class="vg-section__head">
    <h2 class="vg-section__title" style="margin:0; text-align:left;">Nos Menus</h2>
    <a class="vg-btn vg-btn--ghost" href="?r=<?= Route::MENUS ?>">Voir tout</a>
  </div>

  <div class="vg-carousel" data-carousel="all">
    <button class="vg-carousel__nav vg-carousel__nav--prev" type="button" aria-label="Précédent" data-prev>‹</button>

    <div class="vg-carousel__viewport" data-viewport>
      <div class="vg-carousel__track" data-track>
        <?php foreach ($allMenus as $m): ?>
          <article class="vg-card vg-card--menu">
            <img class="vg-card__img" src="<?= htmlspecialchars($menuImg($m)) ?>" alt="<?= htmlspecialchars($m['title'] ?? 'Menu') ?>">
            <div class="vg-card__body">
              <h3 class="vg-card__title"><?= htmlspecialchars($m['title'] ?? 'Menu') ?></h3>
              <div class="vg-card__meta">
                <?php if (!empty($m['theme'])): ?><span class="vg-badge"><?= htmlspecialchars($m['theme']) ?></span><?php endif; ?>
                <?php if (!empty($m['diet'])): ?><span class="vg-badge vg-badge--soft"><?= htmlspecialchars($m['diet']) ?></span><?php endif; ?>
              </div>
              <div class="vg-card__foot">
                <div class="vg-price"><?= isset($m['price']) ? htmlspecialchars((string)$m['price']) : '—' ?>€</div>
                <a class="vg-btn vg-btn--small vg-btn--primary" href="?r=<?= Route::MENU_SHOW ?>&id=<?= (int)($m['id'] ?? 0) ?>">Voir</a>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>

    <button class="vg-carousel__nav vg-carousel__nav--next" type="button" aria-label="Suivant" data-next>›</button>
  </div>
</section>

<!-- Bandeau bas bordeaux -->
<section class="vg-bottombar" aria-hidden="true"></section>

<script src="<?= htmlspecialchars($base) ?>/assets/js/vg-carousels.js?v=1" defer></script>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>
