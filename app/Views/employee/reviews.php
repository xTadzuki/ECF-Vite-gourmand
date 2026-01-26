<?php 
require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Views/layouts/header.php';
 ?>

<h1 class="mb-3">Avis à modérer</h1>

<?php if (empty($reviews)): ?>
  <div class="alert alert-info">Aucun avis en attente.</div>
<?php else: ?>
  <?php foreach ($reviews as $reviews): ?>
    <div class="card mb-2">
      <div class="card-body">
        <strong>Note :</strong> <?= (int)$reviews['rating'] ?>/5<br>
        <p><?= htmlspecialchars($reviews['comment']) ?></p>

        <form method="post" action="?r=employee_review_action" class="d-flex gap-2">
          <input type="hidden" name="review_id" value="<?= $reviews['id'] ?>">
          <button class="btn btn-success" name="action" value="accept">Valider</button>
          <button class="btn btn-danger" name="action" value="reject">Refuser</button>
        </form>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<?php require_once BASE_PATH . '/app/Views/layouts/footer.php';
 ?>
