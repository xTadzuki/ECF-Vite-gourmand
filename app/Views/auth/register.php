<?php
require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Views/layouts/header.php';
?>

<h1 class="mb-3">Créer un compte</h1>

<?php if (!empty($errors)): ?>
  <div class="alert alert-danger" role="alert">
    <ul class="mb-0">
      <?php foreach ($errors as $error): ?>
        <li><?= htmlspecialchars($error) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="post" class="row g-3" novalidate>

  <!-- Prénom -->
  <div class="col-md-6">
    <label class="form-label" for="firstname">Prénom</label>
    <input
      class="form-control"
      id="firstname"
      name="firstname"
      required
      value="<?= htmlspecialchars($old['firstname'] ?? '') ?>"
    >
  </div>

  <!-- Nom -->
  <div class="col-md-6">
    <label class="form-label" for="lastname">Nom</label>
    <input
      class="form-control"
      id="lastname"
      name="lastname"
      required
      value="<?= htmlspecialchars($old['lastname'] ?? '') ?>"
    >
  </div>

  <!-- Email -->
  <div class="col-md-6">
    <label class="form-label" for="email">Email</label>
    <input
      class="form-control"
      id="email"
      name="email"
      type="email"
      required
      value="<?= htmlspecialchars($old['email'] ?? '') ?>"
    >
  </div>

  <!-- Téléphone -->
  <div class="col-md-6">
    <label class="form-label" for="phone">Téléphone</label>
    <input
      class="form-control"
      id="phone"
      name="phone"
      required
      value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
    >
  </div>

  <!-- Adresse -->
  <div class="col-12">
    <label class="form-label" for="address">Adresse postale</label>
    <textarea
      class="form-control"
      id="address"
      name="address"
      rows="2"
      required
    ><?= htmlspecialchars($old['address'] ?? '') ?></textarea>
  </div>

  <!-- Mot de passe + indicateur -->
  <div class="col-md-6">
    <label class="form-label" for="password">Mot de passe</label>
    <input
      class="form-control"
      id="password"
      name="password"
      type="password"
      required
      autocomplete="new-password"
    >

    <div class="form-text">
      10 caractères min, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial.
    </div>

    <!-- Indicateur mot de passe -->
    <div class="mt-2" id="pwdMeter" aria-live="polite">
      <div class="d-flex justify-content-between align-items-center">
        <small class="text-muted">Sécurité du mot de passe</small>
        <small id="pwdScoreLabel" class="text-muted">—</small>
      </div>

      <div class="progress mt-1" style="height:8px;">
        <div
          class="progress-bar"
          id="pwdBar"
          role="progressbar"
          style="width:0%"
        ></div>
      </div>

      <ul class="small mt-2 mb-0" style="padding-left:1.1rem;">
        <li id="rule-len"  class="text-danger">10 caractères minimum</li>
        <li id="rule-up"   class="text-danger">1 majuscule (A-Z)</li>
        <li id="rule-low"  class="text-danger">1 minuscule (a-z)</li>
        <li id="rule-num"  class="text-danger">1 chiffre (0-9)</li>
        <li id="rule-spec" class="text-danger">1 caractère spécial (!@#…)</li>
      </ul>
    </div>
  </div>

  <!-- Confirmation mot de passe -->
  <div class="col-md-6">
    <label class="form-label" for="password2">Confirmer le mot de passe</label>
    <input
      class="form-control"
      id="password2"
      name="password2"
      type="password"
      required
      autocomplete="new-password"
    >
  </div>

  <!-- Actions -->
  <div class="col-12 d-flex flex-wrap gap-2 mt-2">
    <button class="btn btn-primary" type="submit">
      Créer mon compte
    </button>

    <a class="btn btn-outline-secondary" href="?r=<?= Route::LOGIN ?>">
      J’ai déjà un compte
    </a>
  </div>

</form>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>
