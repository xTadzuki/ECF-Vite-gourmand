<?php
// app/Models/UserProfile.php
require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Models/Database.php';

class UserProfile
{
    public static function find(int $id): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT id, firstname, lastname, email, phone, address FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $u = $stmt->fetch();
        return $u ?: null;
    }
}
