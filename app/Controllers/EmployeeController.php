<?php
// app/Controllers/EmployeeController.php
require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/Services/Auth.php';
require_once BASE_PATH . '/app/Models/Order.php';
require_once BASE_PATH . '/app/Models/Review.php';
require_once BASE_PATH . '/app/Services/Mailer.php';
require_once BASE_PATH . '/app/Core/OrderStatus.php';

class EmployeeController
{
    /**
     * Tableau de bord employé / admin
     */
    public function dashboard(): void
    {
        Auth::requireRole(['employee', 'admin']);

        $filters = [
            'status' => $_GET['status'] ?? '',
            'email'  => $_GET['email'] ?? ''
        ];

        $orders = Order::listAll($filters);

        require BASE_PATH . '/app/Views/employee/dashboard.php';
    }

    /**
     * Mise à jour du statut d'une commande
     */
    public function updateStatus(): void
    {
        Auth::requireRole(['employee', 'admin']);

        $orderId = (int)($_POST['order_id'] ?? 0);
        $status  = trim($_POST['status'] ?? '');
        $email   = trim($_POST['email'] ?? '');

        // Sécurité : statut autorisé uniquement
        if ($orderId > 0 && in_array($status, OrderStatus::all(), true)) {
            Order::updateStatusByEmployee($orderId, $status);

            // Notification spécifique : retour matériel
            if ($status === OrderStatus::MATERIAL_RETURN && $email !== '') {
                $mailer = new Mailer('log');
                $mailer->send(
                    $email,
                    "Restitution du matériel sous 10 jours",
                    "Bonjour,\n\nMerci de restituer le matériel sous 10 jours ouvrés.\n"
                    . "À défaut, des frais de 600€ pourront être appliqués.\n\n"
                    . "Cordialement,\nVite & Gourmand"
                );
            }
        }

        header('Location: ?r=employee');
        exit;
    }

    /**
     * Annulation d'une commande par un employé
     */
    public function cancel(): void
    {
        Auth::requireRole(['employee', 'admin']);

        $orderId     = (int)($_POST['order_id'] ?? 0);
        $contactMode = trim($_POST['contact_mode'] ?? '');
        $reason      = trim($_POST['reason'] ?? '');

        if ($orderId > 0 && $contactMode !== '' && $reason !== '') {
            Order::cancelByEmployee($orderId, $contactMode, $reason);
        }

        header('Location: ?r=employee');
        exit;
    }

    /**
     * Liste des avis en attente de validation
     */
    public function reviews(): void
    {
        Auth::requireRole(['employee', 'admin']);

        $reviews = Review::listPending();

        require BASE_PATH . '/app/Views/employee/reviews.php';
    }

    /**
     * Validation ou refus d'un avis
     */
    public function reviewAction(): void
    {
        Auth::requireRole(['employee', 'admin']);

        $reviewId = (int)($_POST['review_id'] ?? 0);
        $action   = $_POST['action'] ?? '';

        if ($reviewId > 0 && in_array($action, ['accept', 'reject'], true)) {
            Review::validate($reviewId, $action === 'accept');
        }

        header('Location: ?r=employee_reviews');
        exit;
    }
}
