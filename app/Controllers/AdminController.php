<?php
// app/Controllers/AdminController.php
require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Services/Auth.php';
require_once BASE_PATH . '/app/Models/Admin.php';
require_once BASE_PATH . '/app/Services/MongoService.php';

class AdminController
{
    public function dashboard(): void
    {
        Auth::requireRole(['admin']);

        $employees = Admin::listEmployees();
        $stats = MongoService::all();

        require_once BASE_PATH . '/app/Views/admin/dashboard.php';
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
        Admin::toggleEmployee((int)$_POST['id']);
       header('Location: ?r=' . Route::ADMIN);
exit;
    }
}
