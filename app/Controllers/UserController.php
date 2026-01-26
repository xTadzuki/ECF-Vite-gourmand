<?php
// app/Controllers/UserController.php

require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Services/Auth.php';
require_once BASE_PATH . '/app/Models/Order.php';
require_once BASE_PATH . '/app/Models/UserProfile.php';

class UserController
{
    public function dashboard(): void
    {
        Auth::requireLogin();

        $orders = Order::listByUser(Auth::userId());
        require_once BASE_PATH . '/app/Views/user/dashboard.php';
    }

    public function showOrder(): void
    {
        Auth::requireLogin();

        $orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($orderId <= 0) {
            http_response_code(400);
            echo "ID de commande invalide";
            return;
        }

        $order = Order::findByIdForUser($orderId, Auth::userId());
        if (!$order) {
            http_response_code(404);
            echo "Commande introuvable";
            return;
        }

        $history = Order::getStatusHistory($orderId);

        // ✅ permet d'afficher les boutons "modifier/annuler" dans la view
        $canEdit = Order::canUserEdit($order);

        require_once BASE_PATH . '/app/Views/user/order_show.php';
    }

    public function editOrder(): void
    {
        Auth::requireLogin();

        $orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($orderId <= 0) {
            http_response_code(400);
            echo "ID invalide";
            return;
        }

        $order = Order::findByIdForUser($orderId, Auth::userId());
        if (!$order) {
            http_response_code(404);
            echo "Commande introuvable";
            return;
        }

        if (!Order::canUserEdit($order)) {
            http_response_code(403);
            echo "Commande non modifiable (déjà acceptée).";
            return;
        }

        $errors = [];
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // ✅ Dans ton schéma : ville + distance (pas event_address)
            $city = trim($_POST['city'] ?? '');
            $km   = (float)($_POST['km'] ?? 0);

            $eventDate   = trim($_POST['event_date'] ?? '');
            $eventTime   = trim($_POST['event_time'] ?? '');
            $peopleCount = (int)($_POST['people_count'] ?? 0);

            if ($city === '') $errors[] = "Ville obligatoire.";
            if ($eventDate === '') $errors[] = "Date obligatoire.";
            if ($eventTime === '') $errors[] = "Heure obligatoire.";

            // ⚠️ on ne peut pas recalculer min_people ici si tu ne le joins pas dans findByIdForUser
            // donc on met juste une règle de base :
            if ($peopleCount <= 0) $errors[] = "Nombre de personnes invalide.";

            if (empty($errors)) {
                $success = Order::updateByUser($orderId, Auth::userId(), [
                    'delivery_city'     => $city,
                    'delivery_distance' => $km,
                    'event_date'        => $eventDate,
                    'event_time'        => $eventTime,
                    'people_count'      => $peopleCount,
                ]);

                // recharge
                $order = Order::findByIdForUser($orderId, Auth::userId());
            }
        }

        require_once BASE_PATH . '/app/Views/user/order_edit.php';
    }

    public function cancelOrder(): void
    {
        Auth::requireLogin();

        $orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($orderId <= 0) {
            http_response_code(400);
            echo "ID invalide";
            return;
        }

        Order::cancelByUser($orderId, Auth::userId());

        header('Location: ?r=' . Route::USER_ORDERS);
        exit;
    }

    public function profile(): void
    {
        Auth::requireLogin();

        $user = UserProfile::find(Auth::userId());
        require_once BASE_PATH . '/app/Views/user/profile.php';
    }
}
