<?php 
require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Views/layouts/header.php';
 ?>

<h1 class="mb-3">Mot de passe oublié</h1>

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
    Si l’adresse existe, un lien de réinitialisation a été envoyé.
    <div class="small text-muted mt-2">
      En local, le “mail” est dans <code>storage/mail.log</code>.
    </div>
  </div>
<?php endif; ?>

<form method="post" class="row g-3" novalidate>
  <div class="col-md-6">
    <label class="form-label" for="email">Votre email</label>
    <input class="form-control" id="email" name="email" type="email" required
           value="<?= htmlspecialchars($oldEmail ?? '') ?>">
  </div>

  <div class="col-12 d-flex gap-2">
    <button class="btn btn-dark" type="submit">Envoyer le lien</button>
    <a class="btn btn-outline-secondary" href="?r=login">Retour connexion</a>
  </div>
</form>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php';
 ?>
