<?php 
require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Views/layouts/header.php';
 ?>

<h1 class="mb-3">Contact</h1>
<p class="text-muted">Une question ? Une demande particulière ? Écris-nous et nous te répondrons rapidement.</p>

<?php if (!empty($success)): ?>
  <div class="alert alert-success" role="alert">
    Message envoyé ✅
    <div class="small text-muted mt-1">En local : <code>storage/mail.log</code></div>
  </div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
  <div class="alert alert-danger" role="alert">
    <ul class="mb-0">
      <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="post" class="row g-3" novalidate>
  <div class="col-md-6">
    <label class="form-label" for="email">Votre email</label>
    <input class="form-control" id="email" name="email" type="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
  </div>

  <div class="col-md-6">
    <label class="form-label" for="title">Titre</label>
    <input class="form-control" id="title" name="title" required value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
  </div>

  <div class="col-12">
    <label class="form-label" for="message">Message</label>
    <textarea class="form-control" id="message" name="message" rows="6" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
  </div>

  <div class="col-12">
    <button class="btn btn-dark" type="submit">Envoyer</button>
  </div>
</form>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php';
 ?>
