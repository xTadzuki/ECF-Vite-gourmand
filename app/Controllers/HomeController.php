<?php
// app/Controllers/HomeController.php

require_once __DIR__ . '/../models/Database.php';

class HomeController
{
    public function index(): void
    {
        $pdo = Database::getConnection();

        // Menus "à la une"
        $stmt = $pdo->query("
            SELECT m.id, m.title, m.description, m.price, m.min_people, m.stock,
                   t.name AS theme, d.name AS diet,
                   (SELECT mi.image_path
                    FROM menu_images mi
                    WHERE mi.menu_id = m.id
                    ORDER BY mi.sort_order ASC, mi.id ASC
                    LIMIT 1) AS thumb
            FROM menus m
            LEFT JOIN themes t ON t.id = m.theme_id
            LEFT JOIN diets d ON d.id = m.diet_id
            ORDER BY m.created_at DESC
            LIMIT 6
        ");
        $featuredMenus = $stmt->fetchAll() ?: [];

        // Menus  — derniers menus
        $stmtMenus = $pdo->query("
            SELECT m.id, m.title, m.description, m.price, m.min_people, m.stock,
                   t.name AS theme, d.name AS diet,
                   (SELECT mi.image_path
                    FROM menu_images mi
                    WHERE mi.menu_id = m.id
                    ORDER BY mi.sort_order ASC, mi.id ASC
                    LIMIT 1) AS thumb
            FROM menus m
            LEFT JOIN themes t ON t.id = m.theme_id
            LEFT JOIN diets d ON d.id = m.diet_id
            ORDER BY m.created_at DESC
            LIMIT 12
        ");
        $menus = $stmtMenus->fetchAll() ?: [];

        // Avis validés 
        $reviews = [];
        try {
            $stmt2 = $pdo->query("
                SELECT r.rating, r.comment, r.created_at
                FROM reviews r
                WHERE r.is_validated = 1
                ORDER BY r.created_at DESC
                LIMIT 10
            ");
            $reviews = $stmt2->fetchAll() ?: [];
        } catch (Throwable $e) {
            $reviews = [];
        }

        require BASE_PATH . '/app/Views/home.php';
    }
}
