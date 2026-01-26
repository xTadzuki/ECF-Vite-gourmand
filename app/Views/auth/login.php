<?php 
require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Views/layouts/header.php';
 ?>

<h1 class="mb-3">Connexion</h1>

<?php if (!empty($errors)): ?>
  <div class="alert alert-danger" role="alert">
    <ul class="mb-0">
      <?php foreach ($errors as $errors): ?>
        <li><?= htmlspecialchars($errors) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="post" class="row g-3" novalidate>
  <div class="col-md-6">
    <label class="form-label" for="email">Email</label>
    <input class="form-control" id="email" name="email" type="email" required
           value="<?= htmlspecialchars($oldEmail ?? '') ?>">
  </div>

  <div class="col-md-6">
    <label class="form-label" for="password">Mot de passe</label>
    <input class="form-control" id="password" name="password" type="password" required>
  </div>

  <div class="col-12 d-flex gap-2">
    <button class="btn btn-dark" type="submit">Se connecter</button>
    <a class="btn btn-outline-secondary" href="?r=register">Créer un compte</a>
    <a class="btn btn-link ms-auto" href="?r=forgot">Mot de passe oublié ?</a>
  </div>
</form>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php';
 ?>
