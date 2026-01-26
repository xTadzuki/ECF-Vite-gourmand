<?php 
require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Views/layouts/header.php';
 ?>

<h1 class="mb-3">Commander : <?= htmlspecialchars($menu['title']) ?></h1>

<?php if (!empty($success)): ?>
  <div class="alert alert-success" role="alert">
    Commande enregistrée ✅ Un email de confirmation a été envoyé.
  </div>
  <a class="btn btn-outline-dark" href="?r=menus">Retour aux menus</a>
<?php else: ?>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger" role="alert">
      <ul class="mb-0">
        <?php foreach ($errors as $errors): ?>
          <li><?= htmlspecialchars($errors) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <div class="card mb-4">
    <div class="card-body">
      <h5 class="mb-2">Infos client (auto-remplies)</h5>
      <div class="row g-2 small">
        <div class="col-md-6"><strong>Nom :</strong> <?= htmlspecialchars(($user['lastname'] ?? '') . ' ' . ($user['firstname'] ?? '')) ?></div>
        <div class="col-md-6"><strong>Email :</strong> <?= htmlspecialchars($user['email'] ?? '') ?></div>
        <div class="col-md-6"><strong>GSM :</strong> <?= htmlspecialchars($user['phone'] ?? '') ?></div>
        <div class="col-md-6"><strong>Adresse :</strong> <?= htmlspecialchars($user['address'] ?? '') ?></div>
      </div>
    </div>
  </div>

  <form method="post" class="row g-3" novalidate>
    <div class="col-12">
      <label class="form-label" for="event_address">Adresse de la prestation</label>
      <input class="form-control" id="event_address" name="event_address" required value="<?= htmlspecialchars($_POST['event_address'] ?? '') ?>">
    </div>

    <div class="col-md-4">
      <label class="form-label" for="event_date">Date</label>
      <input class="form-control" id="event_date" name="event_date" type="date" required value="<?= htmlspecialchars($_POST['event_date'] ?? '') ?>">
    </div>

    <div class="col-md-4">
      <label class="form-label" for="event_time">Heure</label>
      <input class="form-control" id="event_time" name="event_time" type="time" required value="<?= htmlspecialchars($_POST['event_time'] ?? '') ?>">
    </div>

    <div class="col-md-4">
      <label class="form-label" for="people_count">
        Nombre de personnes (min <?= (int)$menu['min_people'] ?>)
      </label>
      <input class="form-control" id="people_count" name="people_count" type="number" min="<?= (int)$menu['min_people'] ?>" required
             value="<?= htmlspecialchars($_POST['people_count'] ?? (int)$menu['min_people']) ?>">
      <div class="form-text">-10% si ≥ <?= (int)$menu['min_people'] + 5 ?> personnes</div>
    </div>

    <div class="col-md-6">
      <label class="form-label" for="city">Ville (livraison)</label>
      <input class="form-control" id="city" name="city" placeholder="Bordeaux"
             value="<?= htmlspecialchars($_POST['city'] ?? 'Bordeaux') ?>">
      <div class="form-text">Bordeaux = livraison gratuite</div>
    </div>

    <div class="col-md-6">
      <label class="form-label" for="km">Distance (km) (démo ECF)</label>
      <input class="form-control" id="km" name="km" type="number" step="0.1"
             value="<?= htmlspecialchars($_POST['km'] ?? '0') ?>">
      <div class="form-text">Si hors Bordeaux : 5€ + 0,59€/km</div>
    </div>

    <div class="col-12">
      <div class="alert alert-warning" role="alert">
        <strong>Conditions du menu :</strong><br>
        <?= nl2br(htmlspecialchars($menu['conditions'] ?? '—')) ?>
      </div>
    </div>

    <div class="col-12">
      <button class="btn btn-primary" type="submit">Valider la commande</button>
      <a class="btn btn-outline-dark" href="?r=menu_show&id=<?= (int)$menu['id'] ?>">Retour</a>
    </div>
  </form>

<?php endif; ?>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php';
 ?>
