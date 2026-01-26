<?php
// app/Views/layouts/footer.php

require_once BASE_PATH . '/app/Core/Route.php';
$hours = null;
try {
  $dbPath = BASE_PATH . '/app/Models/Database.php';
  if (file_exists($dbPath)) {
    require_once $dbPath;
    $pdo = Database::getConnection();
    $stmt = $pdo->query("SELECT day_of_week, open_time, close_time, is_closed FROM opening_hours ORDER BY day_of_week ASC");
    $hours = $stmt->fetchAll();
  }
} catch (Throwable $e) {
  $hours = null; // fallback statique
}

$dayMap = [1=>'Lun',2=>'Mar',3=>'Mer',4=>'Jeu',5=>'Ven',6=>'Sam',7=>'Dim'];

function format_time(?string $t): string {
  if (!$t) return '';
  return substr($t, 0, 5);
}
?>
</main>

<footer class="mt-5 py-4">
  <!-- Accent bar -->
  <div style="
    height: 6px;
    background: linear-gradient(90deg, rgba(122,31,43,1), rgba(201,162,78,1));
  "></div>

  <div class="container pt-4">
    <div class="row g-4 align-items-start">

      <!-- Brand -->
      <div class="col-md-5">
        <div class="d-flex align-items-center gap-2 mb-2">
          <span style="
            width: 38px; height: 38px; display:inline-grid; place-items:center;
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(122,31,43,.95), rgba(201,162,78,.85));
            color:#fff; font-weight:950;
            box-shadow: 0 10px 22px rgba(122,31,43,.18);
          ">VG</span>
          <div>
            <strong style="font-size:18px; display:block; line-height:1.05;">Vite & Gourmand</strong>
            <span class="text-muted small">Traiteur événementiel • Bordeaux</span>
          </div>
        </div>

        <p class="text-muted mb-3">
          Cuisine maison, menus de saison et organisation soignée pour vos événements :
          anniversaire, mariage, entreprise, buffet ou cocktail.
        </p>

        <div class="d-flex flex-wrap gap-2">
          <a class="btn btn-primary" href="?r=contact">Demander un devis</a>
          <a class="btn btn-outline-secondary" href="?r=menus">Voir les menus</a>
        </div>

        <div class="text-muted small mt-3">
          © <?= date('Y') ?> Vite & Gourmand — Tous droits réservés.
        </div>
      </div>

      <!-- Hours -->
      <div class="col-md-3">
        <h6 class="mb-2" style="font-weight:900; letter-spacing:.02em;">Horaires</h6>

        <?php if (is_array($hours) && count($hours) > 0): ?>
          <ul class="list-unstyled text-muted mb-0">
            <?php foreach ($hours as $hours): ?>
              <?php
                $d = (int)($hours['day_of_week'] ?? 0);
                $label = $dayMap[$d] ?? 'Jour';
                $closed = (int)($hours['is_closed'] ?? 0) === 1;
                $open = format_time($hours['open_time'] ?? null);
                $close = format_time($hours['close_time'] ?? null);
              ?>
              <li class="mb-1">
                <strong style="color: rgba(31,26,23,.80);"><?= htmlspecialchars($label) ?></strong>
                : <?= $closed ? 'fermé' : ($open . ' – ' . $close) ?>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <!-- Fallback statique -->
          <ul class="list-unstyled text-muted mb-0">
            <li><strong style="color: rgba(31,26,23,.80);">Lun–Ven</strong> : 09:00 – 18:00</li>
            <li><strong style="color: rgba(31,26,23,.80);">Sam</strong> : 09:00 – 12:00</li>
            <li><strong style="color: rgba(31,26,23,.80);">Dim</strong> : fermé</li>
          </ul>
        <?php endif; ?>

        <div class="text-muted small mt-3">
          Réponse sous 24–48h (jours ouvrés).
        </div>
      </div>

      <!-- Links + Quick contact -->
      <div class="col-md-4">
        <h6 class="mb-2" style="font-weight:900; letter-spacing:.02em;">Liens</h6>
        <div class="d-flex flex-column gap-1 mb-3">
          <a class="footer-link text-muted" href="?r=menus">Menus</a>
          <a class="footer-link text-muted" href="?r=contact">Contact</a>
          <a class="footer-link text-muted" href="?r=mentions">Mentions légales</a>
          <a class="footer-link text-muted" href="?r=cgv">CGV</a>
        </div>

        <div class="card">
          <div class="card-body">
            <div style="font-weight:950;">Contact rapide</div>
            <div class="text-muted small mb-2">Un devis ? Une question allergènes ?</div>

            <div class="d-flex flex-column gap-2">
              <a class="btn btn-dark" href="?r=contact">Écrire un message</a>
              <a class="btn btn-outline-secondary" href="?r=menus">Choisir un menu</a>
            </div>

            <div class="text-muted small mt-2">
              Astuce : sur la page Menus, utilise les filtres “Thème” & “Régime”.
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</footer>

<!-- Bootstrap JS  -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- JS projet -->
<script src="assets/js/app.js"></script>

</body>
</html>
