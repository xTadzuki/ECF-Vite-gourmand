<?php
// app/Models/Order.php

require_once BASE_PATH . '/app/models/Database.php';

class Order
{
    /**
     * Création d'une commande
     */
    public static function create(array $data): int
    {
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("
            INSERT INTO orders
                (user_id, menu_id,
                 event_date, event_time, people_count,
                 delivery_city, delivery_distance, delivery_price,
                 discount, total_price, status,
                 updated_at)
            VALUES
                (:user_id, :menu_id,
                 :event_date, :event_time, :people_count,
                 :delivery_city, :delivery_distance, :delivery_price,
                 :discount, :total_price, :status,
                 NOW())
        ");

        $stmt->execute([
            'user_id'           => (int)$data['user_id'],
            'menu_id'           => (int)$data['menu_id'],
            'event_date'        => $data['event_date'],
            'event_time'        => $data['event_time'],
            'people_count'      => (int)$data['people_count'],
            'delivery_city'     => (string)($data['delivery_city'] ?? 'Bordeaux'),
            'delivery_distance' => (float)($data['delivery_distance'] ?? 0),
            'delivery_price'    => (float)($data['delivery_price'] ?? 0),
            'discount'          => (float)($data['discount'] ?? 0),
            'total_price'       => (float)$data['total_price'],
            'status'            => (string)($data['status'] ?? 'créée'),
        ]);

        return (int)$pdo->lastInsertId();
    }
    public static function history(int $orderId): array
    {
        return self::getStatusHistory($orderId);
    }

    /**
     * Historique des statuts
     */
    public static function addStatusHistory(int $orderId, string $status): void
    {
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("
            INSERT INTO order_status_history (order_id, status, changed_at)
            VALUES (:order_id, :status, NOW())
        ");
        $stmt->execute([
            'order_id' => $orderId,
            'status'   => $status,
        ]);
    }

    /**
     * Décrémente le stock d'un menu d'1
     */
    public static function decreaseMenuStock(int $menuId): void
    {
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("
            UPDATE menus
            SET stock = GREATEST(stock - 1, 0)
            WHERE id = :id
        ");
        $stmt->execute(['id' => $menuId]);
    }

    // =========================================================
    // ESPACE UTILISATEUR
    // =========================================================

    /**
     * Liste des commandes d'un utilisateur
     */
    public static function listByUser(int $userId): array
    {
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("
            SELECT
                o.id,
                o.menu_id,
                o.event_date,
                o.event_time,
                o.people_count,
                o.delivery_city,
                o.delivery_distance,
                o.delivery_price,
                o.discount,
                o.total_price,
                o.status,
                o.created_at,
                m.title AS menu_title
            FROM orders o
            INNER JOIN menus m ON m.id = o.menu_id
            WHERE o.user_id = :user_id
            ORDER BY o.created_at DESC
        ");
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll();
    }

    /**
     * Détail d'une commande appartenant à un user (sécurisé)
     */
    public static function findByIdForUser(int $orderId, int $userId): ?array
    {
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("
            SELECT
                o.*,
                m.title AS menu_title
            FROM orders o
            INNER JOIN menus m ON m.id = o.menu_id
            WHERE o.id = :id AND o.user_id = :user_id
            LIMIT 1
        ");
        $stmt->execute([
            'id'      => $orderId,
            'user_id' => $userId
        ]);

        $order = $stmt->fetch();
        return $order ?: null;
    }

    /**
     * Historique des statuts (pour affichage user/employee/admin)
     */
    public static function getStatusHistory(int $orderId): array
    {
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("
            SELECT status, changed_at
            FROM order_status_history
            WHERE order_id = :order_id
            ORDER BY changed_at ASC
        ");
        $stmt->execute(['order_id' => $orderId]);

        return $stmt->fetchAll();
    }
    /**
     * L'utilisateur peut modifier/annuler uniquement si la commande est "créée"
     */
    public static function canUserEdit(array $order): bool
    {
        return (($order['status'] ?? '') === 'créée');
    }

    /**
     * Mise à jour d'une commande par son propriétaire (si modifiable)
     */
    public static function updateByUser(int $orderId, int $userId, array $data): bool
    {
        $pdo = Database::getConnection();

        // On autorise uniquement ces champs
        $allowed = ['delivery_city', 'delivery_distance', 'event_date', 'event_time', 'people_count'];
        $set = [];
        $params = ['id' => $orderId, 'user_id' => $userId];

        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $set[] = "$field = :$field";
                $params[$field] = $data[$field];
            }
        }

        if (empty($set)) return false;

        // Sécurité: n'update que si status = 'créée'
        $sql = "
        UPDATE orders
        SET " . implode(', ', $set) . ", updated_at = NOW()
        WHERE id = :id AND user_id = :user_id AND status = 'créée'
    ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    /**
     * Annulation par l'utilisateur (si modifiable)
     */
    public static function cancelByUser(int $orderId, int $userId): bool
    {
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("
        UPDATE orders
        SET status = 'annulée',
            cancelled_at = NOW(),
            updated_at = NOW()
        WHERE id = :id AND user_id = :user_id AND status = 'créée'
    ");
        $stmt->execute([
            'id' => $orderId,
            'user_id' => $userId
        ]);

        if ($stmt->rowCount() > 0) {
            self::addStatusHistory($orderId, 'annulée');
            return true;
        }

        return false;
    }

    // =========================================================
    // ESPACE EMPLOYÉ / ADMIN
    // =========================================================

    /**
     * Liste de toutes les commandes (avec filtres)
     * Filters: status, email
     */
    public static function listAll(array $filters = []): array
    {
        $pdo = Database::getConnection();

        $where = [];
        $params = [];

        if (!empty($filters['status'])) {
            $where[] = "o.status = :status";
            $params['status'] = $filters['status'];
        }

        if (!empty($filters['email'])) {
            $where[] = "u.email LIKE :email";
            $params['email'] = '%' . $filters['email'] . '%';
        }

        $sql = "
            SELECT
                o.*,
                u.email,
                m.title AS menu_title
            FROM orders o
            INNER JOIN users u ON u.id = o.user_id
            INNER JOIN menus m ON m.id = o.menu_id
        ";

        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sql .= " ORDER BY o.created_at DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * Changement de statut par employé/admin
     */
    public static function updateStatusByEmployee(int $orderId, string $status): void
    {
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("
            UPDATE orders
            SET status = :status, updated_at = NOW()
            WHERE id = :id
        ");
        $stmt->execute([
            'status' => $status,
            'id'     => $orderId,
        ]);

        self::addStatusHistory($orderId, $status);
    }

    /**
     * Annulation par employé/admin (stocke le mode de contact + raison + date)
     */
    public static function cancelByEmployee(int $orderId, string $contactMode, string $reason): void
    {
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("
            UPDATE orders
            SET status = 'annulée',
                cancel_contact_mode = :mode,
                cancel_reason = :reason,
                cancelled_at = NOW(),
                updated_at = NOW()
            WHERE id = :id
        ");
        $stmt->execute([
            'mode'   => $contactMode,
            'reason' => $reason,
            'id'     => $orderId,
        ]);

        self::addStatusHistory($orderId, 'annulée');
    }



    // --- STATS (DAL) 

    public static function statsOrdersByStatus(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("
            SELECT status, COUNT(*) AS total
            FROM orders
            GROUP BY status
            ORDER BY total DESC
        ");
        return $stmt->fetchAll() ?: [];
    }

    public static function statsRevenueByMonth(int $months = 12): array
    {
        $months = max(1, min(24, $months));
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, SUM(total_price) AS revenue, COUNT(*) AS orders_count
            FROM orders
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL :m MONTH)
            GROUP BY ym
            ORDER BY ym ASC
        ");
        $stmt->bindValue(':m', $months, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll() ?: [];
    }

    public static function statsTopMenus(int $limit = 5): array
    {
        $limit = max(1, min(20, $limit));
        $pdo = Database::getConnection();
        $sql = "
            SELECT m.id, m.title, COUNT(o.id) AS orders_count
            FROM orders o
            INNER JOIN menus m ON m.id = o.menu_id
            GROUP BY m.id, m.title
            ORDER BY orders_count DESC
            LIMIT {$limit}
        ";
        return $pdo->query($sql)->fetchAll() ?: [];
    }
}
