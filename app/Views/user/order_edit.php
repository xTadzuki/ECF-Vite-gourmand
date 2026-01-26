<?php
// app/Views/user/order_edit.php
require_once BASE_PATH . '/app/Views/layouts/header.php';

$order = $order ?? [];
$errorsList = $errors ?? [];
?>

<h1 class="mb-3">Modifier la commande #<?= (int)($order['id'] ?? 0) ?></h1>

<?php if (!empty($success)): ?>
  <div class="alert alert-success">Commande modifiée ✅</div>
<?php endif; ?>

<?php if (!empty($errorsList)): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($errorsList as $err): ?>
        <li><?= htmlspecialchars($err) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<div class="alert alert-info">
  Le menu n’est pas modifiable. Vous pouvez modifier la ville (et la distance), la date/heure et le nombre de personnes.
</div>

<form method="post" class="row g-3" novalidate>

  <div class="col-md-8">
    <label class="form-label" for="city">Ville de livraison</label>
    <input
      class="form-control"
      id="city"
      name="city"
      required
      placeholder="Ex: Bordeaux"
      value="<?= htmlspecialchars($_POST['city'] ?? ($order['delivery_city'] ?? '')) ?>"
    >
  </div>

  <div class="col-md-4">
    <label class="form-label" for="km">Distance (km)</label>
    <input
      class="form-control"
      id="km"
      name="km"
      type="number"
      step="0.1"
      min="0"
      placeholder="Ex: 12.5"
      value="<?= htmlspecialchars($_POST['km'] ?? ($order['delivery_distance'] ?? 0)) ?>"
    >
    <div class="form-text">0 km si Bordeaux (livraison offerte).</div>
  </div>

  <div class="col-md-4">
    <label class="form-label" for="event_date">Date</label>
    <input
      class="form-control"
      id="event_date"
      name="event_date"
      type="date"
      required
      value="<?= htmlspecialchars($_POST['event_date'] ?? ($order['event_date'] ?? '')) ?>"
    >
  </div>

  <div class="col-md-4">
    <label class="form-label" for="event_time">Heure</label>
    <input
      class="form-control"
      id="event_time"
      name="event_time"
      type="time"
      required
      value="<?= htmlspecialchars($_POST['event_time'] ?? ($order['event_time'] ?? '')) ?>"
    >
  </div>

  <div class="col-md-4">
    <label class="form-label" for="people_count">Nombre de personnes</label>
    <input
      class="form-control"
      id="people_count"
      name="people_count"
      type="number"
      min="1"
      required
      value="<?= htmlspecialchars($_POST['people_count'] ?? ($order['people_count'] ?? '')) ?>"
    >
  </div>

  <div class="col-12 d-flex gap-2">
    <button class="btn btn-dark" type="submit">Enregistrer</button>
    <a class="btn btn-outline-secondary" href="?r=user_order_show&id=<?= (int)($order['id'] ?? 0) ?>">Annuler</a>
  </div>
</form>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php'; ?>
