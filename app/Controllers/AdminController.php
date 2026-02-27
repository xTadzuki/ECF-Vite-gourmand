<?php
// app/Controllers/AdminController.php
require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Services/Auth.php';

/**
 * Compat Linux : selon ton repo, le dossier peut être app/models ou app/Models
 */
$modelsDir = BASE_PATH . '/app/models';
if (!is_dir($modelsDir)) {
    $modelsDir = BASE_PATH . '/app/Models';
}

require_once $modelsDir . '/Admin.php';
require_once $modelsDir . '/Menu.php';
require_once $modelsDir . '/Order.php';

require_once BASE_PATH . '/app/Services/MongoService.php';

class AdminController
{
    public function dashboard(): void
    {
        Auth::requireRole(['admin']);

        $employees = Admin::listEmployees();
        $stats = MongoService::all();

        $view = BASE_PATH . '/app/Views/admin/dashboard.php';
        if (!file_exists($view)) $view = BASE_PATH . '/app/views/admin/dashboard.php';
        require_once $view;
    }

    public function createEmployee(): void
    {
        Auth::requireRole(['admin']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Admin::createEmployee($_POST['email'], $_POST['password']);
        }
        header('Location: ?r=' . Route::ADMIN);
        exit;
    }

    public function toggleEmployee(): void
    {
        Auth::requireRole(['admin']);
        Admin::toggleEmployee((int)($_POST['id'] ?? 0));
        header('Location: ?r=' . Route::ADMIN);
        exit;
    }

    public function statsJson(): void
    {
        Auth::requireRole(['admin']);
        header('Content-Type: application/json; charset=utf-8');

        $ordersByStatus = Order::statsOrdersByStatus();
        $revenueByMonth = Order::statsRevenueByMonth(12);
        $topMenus       = Order::statsTopMenus(6);

        $mongo = MongoService::all();

        echo json_encode([
            'success'        => true,
            'ordersByStatus' => $ordersByStatus,
            'revenueByMonth' => $revenueByMonth,
            'topMenus'       => $topMenus,
            'mongo'          => $mongo,
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // --- MENUS CRUD

    public function menus(): void
    {
        Auth::requireRole(['admin']);

        // --- Diagnostic : affiche toujours quelque chose, même en cas d'erreur ---
        $menus = [];
        $dbError = null;

        try {
            $menus = Menu::all();
        } catch (\Throwable $e) {
            $dbError = $e->getMessage();
        }

        // Si erreur BDD : affiche un message clair AVANT tout layout
        if ($dbError !== null) {
            http_response_code(500);
            require_once BASE_PATH . '/app/Views/layouts/header.php';
            echo '<div class="container mt-4">';
            echo '<div class="alert alert-danger">';
            echo '<h4>Erreur de connexion à la base de données</h4>';
            echo '<pre>' . htmlspecialchars($dbError) . '</pre>';
            echo '<hr>';
            echo '<p>Vérifie que les variables d\'environnement suivantes sont bien définies sur Fly.io :</p>';
            echo '<ul>';
            echo '<li><code>DB_HOST</code></li>';
            echo '<li><code>DB_PORT</code></li>';
            echo '<li><code>DB_NAME</code></li>';
            echo '<li><code>DB_USER</code></li>';
            echo '<li><code>DB_PASS</code></li>';
            echo '</ul>';
            echo '<p>Commande : <code>fly secrets set DB_HOST=... DB_NAME=... DB_USER=... DB_PASS=...</code></p>';
            echo '</div></div>';
            require_once BASE_PATH . '/app/Views/layouts/footer.php';
            return;
        }

        $view = BASE_PATH . '/app/Views/admin/menus/index.php';

        if (!file_exists($view)) {
            http_response_code(500);
            echo "Vue introuvable : " . htmlspecialchars($view);
            return;
        }

        require_once $view;
    }

    public function menuCreate(): void
    {
        Auth::requireRole(['admin']);

        $menu = [
            'title'       => '',
            'description' => '',
            'min_people'  => 1,
            'price'       => 0,
            'stock'       => 0,
            'theme_id'    => null,
            'diet_id'     => null,
        ];

        $themes = Menu::themes();
        $diets  = Menu::diets();

        $view = BASE_PATH . '/app/Views/admin/menus/create.php';
        if (!file_exists($view)) $view = BASE_PATH . '/app/Views/admin/menus/create.php';
        require_once $view;
    }

    public function menuStore(): void
    {
        Auth::requireRole(['admin']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?r=' . Route::ADMIN_MENUS);
            exit;
        }

        $data = $this->sanitizeMenuPost($_POST);
        Menu::create($data);

        header('Location: ?r=' . Route::ADMIN_MENUS);
        exit;
    }

    public function menuEdit(): void
    {
        Auth::requireRole(['admin']);

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            echo "ID invalide";
            exit;
        }

        $menu = Menu::find($id);
        if (!$menu) {
            http_response_code(404);
            echo "Menu introuvable";
            exit;
        }

        $themes = Menu::themes();
        $diets  = Menu::diets();

        $view = BASE_PATH . '/app/Views/admin/menus/edit.php';
        if (!file_exists($view)) $view = BASE_PATH . '/app/Views/admin/menus/edit.php';
        require_once $view;
    }

    public function menuUpdate(): void
    {
        Auth::requireRole(['admin']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?r=' . Route::ADMIN_MENUS);
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            echo "ID invalide";
            exit;
        }

        $data = $this->sanitizeMenuPost($_POST);
        Menu::update($id, $data);

        header('Location: ?r=' . Route::ADMIN_MENUS);
        exit;
    }

    public function menuDelete(): void
    {
        Auth::requireRole(['admin']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?r=' . Route::ADMIN_MENUS);
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            Menu::delete($id);
        }

        header('Location: ?r=' . Route::ADMIN_MENUS);
        exit;
    }

    private function sanitizeMenuPost(array $post): array
    {
        return [
            'title'       => trim((string)($post['title'] ?? '')),
            'description' => trim((string)($post['description'] ?? '')),
            'min_people'  => (int)($post['min_people'] ?? 1),
            'price'       => (float)($post['price'] ?? 0),
            'stock'       => (int)($post['stock'] ?? 0),
            'theme_id'    => ($post['theme_id'] ?? '') !== '' ? (int)$post['theme_id'] : null,
            'diet_id'     => ($post['diet_id'] ?? '') !== '' ? (int)$post['diet_id'] : null,
        ];
    }
}
