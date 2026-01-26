<?php
require_once BASE_PATH . '/app/Views/layouts/header.php';
?>

<section class="section">
  <div class="mb-2">
    <h2 class="section-title">Menus du moment</h2>
    <p class="section-subtitle">Une sélection courte pour vous guider.</p>
  </div>

  <?php if (empty($featuredMenus)): ?>
    <div class="text-muted">Aucun menu disponible pour le moment.</div>
  <?php else: ?>
    <div class="row g-3">
      <?php foreach ($featuredMenus as $featuredMenus): ?>
        <div class="col-12 col-md-6 col-lg-4">
          <div class="card h-100">
            <div class="card-body d-flex flex-column">
              <div class="mb-2">
                <div class="card-title"><?= htmlspecialchars($featuredMenus['title'] ?? '') ?></div>
                <div class="text-muted small">
                  <?= htmlspecialchars($featuredMenus['theme'] ?? '—') ?> •
                  <?= htmlspecialchars($featuredMenus['diet'] ?? '—') ?>
                </div>
              </div>

              <p class="text-muted mb-3">
                <?= nl2br(htmlspecialchars(mb_strimwidth($featuredMenus['description'] ?? '', 0, 100, '…'))) ?>
              </p>

              <div class="mt-auto d-flex justify-content-between align-items-center">
                <div>
                  <div class="text-muted small">À partir de</div>
                  <strong><?= number_format((float)($featuredMenus['price'] ?? 0), 2, ',', ' ') ?> €</strong>
                  <span class="text-muted small">/ pers.</span>
                </div>

                <a class="btn btn-dark btn-sm"
                   href="?r=menu_show&id=<?= (int)($featuredMenus['id'] ?? 0) ?>">
                  Voir le menu
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<section class="section">
  <div class="mb-2">
    <h2 class="section-title">Pourquoi nous Choisir</h2>
  </div>
  <div class="row g-3">
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="badge-soft mb-2">🍽️ Qualité</div>
          <strong>Produits sélectionnés</strong>
          <p class="text-muted mb-0">Cuisine maison, présentation soignée.</p>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="badge-soft mb-2">⚠️ Transparence</div>
          <strong>Allergènes clairs</strong>
          <p class="text-muted mb-0">Informations visibles avant commande.</p>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="badge-soft mb-2">📦 Suivi</div>
          <strong>Commande maîtrisée</strong>
          <p class="text-muted mb-0">Statuts et notifications.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
require_once BASE_PATH . '/app/Views/layouts/footer.php';
?>
