<?php
// app/Models/Admin.php
require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Models/Database.php';

class Admin
{
    public static function createEmployee(string $email, string $password): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO users (role_id, email, password, is_active)
            VALUES (2, :email, :password, 1)
        ");
        $stmt->execute([
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }

    public static function listEmployees(): array
    {
        $pdo = Database::getConnection();
        return $pdo->query("
            SELECT id, email, is_active
            FROM users
            WHERE role_id = 2
            ORDER BY email
        ")->fetchAll();
    }

    public static function toggleEmployee(int $id): void
    {
        $pdo = Database::getConnection();
        $pdo->prepare("
            UPDATE users
            SET is_active = NOT is_active
            WHERE id = :id AND role_id = 2
        ")->execute(['id' => $id]);
    }
}
