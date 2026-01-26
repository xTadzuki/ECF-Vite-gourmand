<?php
// app/Models/Review.php
require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Models/Database.php';

class Review
{
    public static function listPending(): array
    {
        $pdo = Database::getConnection();
        return $pdo->query("
            SELECT r.*, o.id AS order_id
            FROM reviews r
            INNER JOIN orders o ON o.id = r.order_id
            WHERE r.is_validated = 0
            ORDER BY r.created_at DESC
        ")->fetchAll();
    }

    public static function validate(int $id, bool $value): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE reviews SET is_validated = :v WHERE id = :id");
        $stmt->execute([
            'v' => $value ? 1 : 0,
            'id' => $id
        ]);
    }
}
