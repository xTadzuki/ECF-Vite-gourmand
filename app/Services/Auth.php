<?php
// app/Services/Auth.php

class Auth
{
    public static function isLogged(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    public static function userId(): ?int
    {
        return self::isLogged() ? (int)$_SESSION['user_id'] : null;
    }

    public static function role(): ?string
    {
        return self::isLogged() ? ($_SESSION['role'] ?? null) : null;
    }

    public static function requireLogin(): void
    {
        if (!self::isLogged()) {
            header('Location: ?r=login');
            exit;
        }
    }

    public static function requireRole(array $roles): void
    {
        self::requireLogin();
        if (!in_array(self::role(), $roles, true)) {
            http_response_code(403);
            echo "403 - Accès refusé";
            exit;
        }
    }
}
