<?php
// app/Controllers/MenuController.php

require_once BASE_PATH . '/app/Models/Menu.php';

class MenuController
{
    public function index(): void
    {
        $menus = Menu::all();
        require BASE_PATH . '/app/Views/menus/index.php';
    }

    public function show(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            http_response_code(400);
            echo "ID invalide";
            return;
        }

        $menu = Menu::find($id);
        if (!$menu) {
            http_response_code(404);
            echo "Menu introuvable";
            return;
        }

        $images = Menu::images($id);
        $dishes = Menu::dishesWithAllergens($id);

        require BASE_PATH . '/app/Views/menus/show.php';
    }

    public function json(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $filters = [
            'price_max'       => $_GET['price_max'] ?? '',
            'price_min'       => $_GET['price_min'] ?? '',
            'price_max_range' => $_GET['price_max_range'] ?? '',
            'theme_id'        => $_GET['theme_id'] ?? '',
            'diet_id'         => $_GET['diet_id'] ?? '',
            'min_people'      => $_GET['min_people'] ?? '',
        ];

        $menus = Menu::filter($filters);

        echo json_encode([
            'success' => true,
            'count'   => count($menus),
            'menus'   => $menus
        ]);
    }
}
