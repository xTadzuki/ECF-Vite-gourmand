<?php require_once BASE_PATH . '/app/Views/layouts/header.php'; ?>

<div class="hero mb-4">
  <div class="d-flex flex-wrap justify-content-between align-items-end gap-2">
    <div>
      <h1 class="mb-1">Mes informations</h1>
      <p class="text-muted mb-0">Retrouve tes coordonnées utilisées pour tes commandes.</p>
    </div>

    <div class="d-flex gap-2">
      <a class="btn btn-outline-dark btn-sm" href="?r=user_orders">← Mes commandes</a>
    </div>
  </div>
</div>

<?php if (!$user): ?>
  <div class="alert alert-danger">Utilisateur introuvable.</div>
<?php else: ?>

  <?php
    $fullname = trim(($user['firstname'] ?? '') . ' ' . ($user['lastname'] ?? ''));
    $email    = (string)($user['email'] ?? '');
    $phone    = (string)($user['phone'] ?? '');
    $address  = (string)($user['address'] ?? '');
  ?>

  <div class="row g-3">
    <div class="col-12 col-lg-8">
      <div class="card">
        <div class="card-body">

          <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
            <div>
              <div class="text-muted small">Profil</div>
              <div style="font-weight:950; font-size:20px;">
                <?= htmlspecialchars($fullname !== '' ? $fullname : '—') ?>
              </div>
              <div class="text-muted small">Informations personnelles</div>
            </div>

            <div class="d-none d-md-flex align-items-center justify-content-center"
                 style="width:44px;height:44px;border-radius:14px;background:rgba(0,0,0,.06);">
              <span style="font-weight:900;">👤</span>
            </div>
          </div>

          <div class="list-group list-group-flush">
            <div class="list-group-item px-0 d-flex align-items-start gap-3">
              <div style="width:34px;height:34px;border-radius:12px;background:rgba(0,0,0,.06);display:grid;place-items:center;">
                ✉️
              </div>
              <div class="flex-grow-1">
                <div class="text-muted small">Email</div>
                <div style="font-weight:700;"><?= htmlspecialchars($email !== '' ? $email : '—') ?></div>
              </div>
            </div>

            <div class="list-group-item px-0 d-flex align-items-start gap-3">
              <div style="width:34px;height:34px;border-radius:12px;background:rgba(0,0,0,.06);display:grid;place-items:center;">
                📞
              </div>
              <div class="flex-grow-1">
                <div class="text-muted small">Téléphone</div>
                <div style="font-weight:700;"><?= htmlspecialchars($phone !== '' ? $phone : '—') ?></div>
              </div>
            </div>

            <div class="list-group-item px-0 d-flex align-items-start gap-3">
              <div style="width:34px;height:34px;border-radius:12px;background:rgba(0,0,0,.06);display:grid;place-items:center;">
                📍
              </div>
              <div class="flex-grow-1">
                <div class="text-muted small">Adresse</div>
                <div style="font-weight:700; white-space:pre-line;"><?= htmlspecialchars($address !== '' ? $address : '—') ?></div>
              </div>
            </div>
          </div>

          <div class="d-flex flex-wrap gap-2 mt-4">
            <a class="btn btn-outline-dark" href="?r=user_orders">← Mes commandes</a>
            <a class="btn btn-dark" href="?r=user_orders">Voir mes commandes</a>
          </div>

        </div>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="text-muted small mb-1">Conseil</div>
          <div style="font-weight:900; font-size:18px;" class="mb-2">Informations utilisées</div>

          <p class="text-muted mb-3">
            Ces coordonnées peuvent être utilisées pour t’identifier, recevoir les emails de suivi
            et faciliter la prise de contact.
          </p>

        </div>
      </div>
    </div>
  </div>

<?php endif; ?>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>
