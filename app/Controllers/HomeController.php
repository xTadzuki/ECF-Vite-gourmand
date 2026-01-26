<?php
// app/Controllers/HomeController.php

require_once BASE_PATH . '/app/Models/Database.php';

class HomeController
{
    public function index(): void
    {
        $pdo = Database::getConnection();

        // 3 menus "à la une" (les plus récents)
        $stmt = $pdo->query("
            SELECT m.id, m.title, m.description, m.price, m.min_people, m.stock,
                   t.name AS theme, d.name AS diet
            FROM menus m
            LEFT JOIN themes t ON t.id = m.theme_id
            LEFT JOIN diets d ON d.id = m.diet_id
            ORDER BY m.created_at DESC
            LIMIT 3
        ");
        $featuredMenus = $stmt->fetchAll() ?: [];

        // Avis validés (max 3)
        $reviews = [];
        try {
            $stmt2 = $pdo->query("
                SELECT r.rating, r.comment, r.created_at
                FROM reviews r
                WHERE r.is_validated = 1
                ORDER BY r.created_at DESC
                LIMIT 3
            ");
            $reviews = $stmt2->fetchAll() ?: [];
        } catch (Throwable $e) {
            $reviews = [];
        }

        require BASE_PATH . '/app/Views/home.php';
    }
}
