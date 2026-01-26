<?php 
require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Views/layouts/header.php';
 ?>

<h1 class="mb-3">Réinitialiser le mot de passe</h1>

<?php if (!empty($errors)): ?>
  <div class="alert alert-danger" role="alert">
    <ul class="mb-0">
      <?php foreach ($errors as $errors): ?>
        <li><?= htmlspecialchars($errors) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
  <div class="alert alert-success" role="alert">
    Mot de passe modifié. Vous pouvez vous connecter.
  </div>
  <a class="btn btn-primary" href="?r=login">Aller à la connexion</a>
<?php else: ?>
  <form method="post" class="row g-3" novalidate>
    <div class="col-md-6">
      <label class="form-label" for="password">Nouveau mot de passe</label>
      <input class="form-control" id="password" name="password" type="password" required>
      <div class="form-text">
        10 caractères minimum, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial.
      </div>
    </div>

    <div class="col-md-6">
      <label class="form-label" for="password2">Confirmer</label>
      <input class="form-control" id="password2" name="password2" type="password" required>
    </div>

    <div class="col-12">
      <button class="btn btn-dark" type="submit">Mettre à jour</button>
      <a class="btn btn-outline-secondary" href="?r=login">Retour connexion</a>
    </div>
  </form>
<?php endif; ?>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php';
 ?>
