<?php
// app/models/Database.php

class Database
{
    private static ?PDO $pdo = null;

    public static function getConnection(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        // Par défaut (LOCAL XAMPP/WAMP)
        $host    = getenv('DB_HOST') ?: '127.0.0.1';
        $port    = getenv('DB_PORT') ?: '3306';
        $db      = getenv('DB_NAME') ?: 'vite_gourmand';
        $user    = getenv('DB_USER') ?: 'root';
        $pass    = getenv('DB_PASS') ?: '';
        $charset = getenv('DB_CHARSET') ?: 'utf8mb4';

        // DSN avec port 
        $dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";

        try {
            self::$pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            // Message clair en prod (sans leak du mot de passe)
            throw new PDOException(
                "Connexion BDD impossible (host={$host}, db={$db}). Vérifie DB_HOST/DB_NAME/DB_USER/DB_PASS/DB_PORT. Détail: " . $e->getMessage(),
                (int)$e->getCode()
            );
        }

        return self::$pdo;
    }
}
