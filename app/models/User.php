<?php
// app/Models/User.php
require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Models/Database.php';

class User
{
    public static function findByEmail(string $email): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT u.*, r.name AS role_name
            FROM users u
            INNER JOIN roles r ON r.id = u.role_id
            WHERE u.email = :email
            LIMIT 1
        ");
        $stmt->execute(['email' => $email]);
        $u = $stmt->fetch();
        return $u ?: null;
    }

    public static function createUser(array $data): int
    {
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("
            INSERT INTO users (role_id, firstname, lastname, email, password, phone, address, is_active)
            VALUES (:role_id, :firstname, :lastname, :email, :password, :phone, :address, 1)
        ");

        $stmt->execute([
            'role_id'   => 1, // user
            'firstname' => $data['firstname'],
            'lastname'  => $data['lastname'],
            'email'     => $data['email'],
            'password'  => $data['password'],
            'phone'     => $data['phone'],
            'address'   => $data['address'],
        ]);

        return (int)$pdo->lastInsertId();
    }

    public static function isEmailTaken(string $email): bool
    {
        return self::findByEmail($email) !== null;
    }
    public static function setResetToken(int $userId, string $token, string $expiresAt): void
{
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare("
        UPDATE users
        SET reset_token = :token, reset_expires_at = :expires
        WHERE id = :id
    ");
    $stmt->execute([
        'token' => $token,
        'expires' => $expiresAt,
        'id' => $userId
    ]);
}

public static function findByResetToken(string $token): ?array
{
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare("
        SELECT u.*, r.name AS role_name
        FROM users u
        INNER JOIN roles r ON r.id = u.role_id
        WHERE u.reset_token = :token
        LIMIT 1
    ");
    $stmt->execute(['token' => $token]);
    $u = $stmt->fetch();
    return $u ?: null;
}

public static function updatePasswordAndClearReset(int $userId, string $hash): void
{
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare("
        UPDATE users
        SET password = :password, reset_token = NULL, reset_expires_at = NULL
        WHERE id = :id
    ");
    $stmt->execute([
        'password' => $hash,
        'id' => $userId
    ]);
}

}
