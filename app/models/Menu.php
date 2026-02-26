<?php
// app/Models/Menu.php
require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/models/Database.php';

class Menu
{
    public static function all(): array
    {
        $pdo = Database::getConnection();
        $sql = "
            SELECT 
                m.id, m.title, m.description, m.min_people, m.price, m.stock,
                (
                  SELECT mi.image_path
                  FROM menu_images mi
                  WHERE mi.menu_id = m.id
                  ORDER BY mi.sort_order ASC, mi.id ASC
                  LIMIT 1
                ) AS thumb,
                t.name AS theme,
                d.name AS diet
            FROM menus m
            LEFT JOIN themes t ON t.id = m.theme_id
            LEFT JOIN diets d ON d.id = m.diet_id
            ORDER BY m.created_at DESC
        ";
        return $pdo->query($sql)->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::getConnection();

        $sql = "
        SELECT 
            m.*,
            t.name AS theme,
            d.name AS diet
        FROM menus m
        LEFT JOIN themes t ON t.id = m.theme_id
        LEFT JOIN diets d ON d.id = m.diet_id
        WHERE m.id = :id
        LIMIT 1
    ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $menu = $stmt->fetch(PDO::FETCH_ASSOC);
        return $menu ?: null;
    }


    public static function images(int $menuId): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT image_path, alt_text
            FROM menu_images
            WHERE menu_id = :menu_id
            ORDER BY sort_order ASC, id ASC
        ");
        $stmt->execute(['menu_id' => $menuId]);
        return $stmt->fetchAll();
    }

    public static function dishesWithAllergens(int $menuId): array
    {
        $pdo = Database::getConnection();

        // récupère les plats du menu
        $stmt = $pdo->prepare("
            SELECT di.id, di.name, di.type
            FROM dishes di
            INNER JOIN menu_dish md ON md.dish_id = di.id
            WHERE md.menu_id = :menu_id
            ORDER BY FIELD(di.type,'entrée','plat','dessert'), di.name
        ");
        $stmt->execute(['menu_id' => $menuId]);
        $dishes = $stmt->fetchAll();

        if (!$dishes) return [];

        // récupère les allergènes de ces plats (en une requête)
        $dishIds = array_column($dishes, 'id');
        $placeholders = implode(',', array_fill(0, count($dishIds), '?'));

        $stmt2 = $pdo->prepare("
            SELECT da.dish_id, a.name AS allergen
            FROM dish_allergen da
            INNER JOIN allergens a ON a.id = da.allergen_id
            WHERE da.dish_id IN ($placeholders)
            ORDER BY a.name
        ");
        $stmt2->execute($dishIds);
        $rows = $stmt2->fetchAll();

        // groupe par dish_id
        $allergensByDish = [];
        foreach ($rows as $r) {
            $allergensByDish[(int)$r['dish_id']][] = $r['allergen'];
        }

        // injecte dans $dishes
        foreach ($dishes as &$dish) {
            $dishId = (int)$dish['id'];
            $dish['allergens'] = $allergensByDish[$dishId] ?? [];
        }

        return $dishes;
    }
    public static function filter(array $filters): array
    {
        $pdo = Database::getConnection();

        $where = [];
        $params = [];

        // prix max
        if (isset($filters['price_max']) && $filters['price_max'] !== '') {
            $where[] = "m.price <= :price_max";
            $params['price_max'] = (float)$filters['price_max'];
        }

        // fourchette min/max
        if (isset($filters['price_min']) && $filters['price_min'] !== '') {
            $where[] = "m.price >= :price_min";
            $params['price_min'] = (float)$filters['price_min'];
        }
        if (isset($filters['price_max_range']) && $filters['price_max_range'] !== '') {
            $where[] = "m.price <= :price_max_range";
            $params['price_max_range'] = (float)$filters['price_max_range'];
        }

        // thème
        if (isset($filters['theme_id']) && $filters['theme_id'] !== '') {
            $where[] = "m.theme_id = :theme_id";
            $params['theme_id'] = (int)$filters['theme_id'];
        }

        // régime
        if (isset($filters['diet_id']) && $filters['diet_id'] !== '') {
            $where[] = "m.diet_id = :diet_id";
            $params['diet_id'] = (int)$filters['diet_id'];
        }

        // nb personnes minimum
        if (isset($filters['min_people']) && $filters['min_people'] !== '') {
            $where[] = "m.min_people >= :min_people";
            $params['min_people'] = (int)$filters['min_people'];
        }

        $sql = "
        SELECT 
            m.id, m.title, m.description, m.min_people, m.price, m.stock,
            (
              SELECT mi.image_path
              FROM menu_images mi
              WHERE mi.menu_id = m.id
              ORDER BY mi.sort_order ASC, mi.id ASC
              LIMIT 1
            ) AS thumb,
            t.name AS theme,
            d.name AS diet
        FROM menus m
        LEFT JOIN themes t ON t.id = m.theme_id
        LEFT JOIN diets d ON d.id = m.diet_id
    ";

        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sql .= " ORDER BY m.created_at DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }



    // ADMIN CRUD (DAL) 

    public static function themes(): array
    {
        $pdo = Database::getConnection();
        return $pdo->query("SELECT id, name FROM themes ORDER BY name ASC")->fetchAll() ?: [];
    }

    public static function diets(): array
    {
        $pdo = Database::getConnection();
        return $pdo->query("SELECT id, name FROM diets ORDER BY name ASC")->fetchAll() ?: [];
    }

    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO menus (title, description, min_people, price, stock, theme_id, diet_id, created_at)
            VALUES (:title, :description, :min_people, :price, :stock, :theme_id, :diet_id, NOW())
        ");
        $stmt->execute([
            ':title'       => $data['title'],
            ':description' => $data['description'],
            ':min_people'  => (int)$data['min_people'],
            ':price'       => (float)$data['price'],
            ':stock'       => (int)$data['stock'],
            ':theme_id'    => !empty($data['theme_id']) ? (int)$data['theme_id'] : null,
            ':diet_id'     => !empty($data['diet_id']) ? (int)$data['diet_id'] : null,
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            UPDATE menus
            SET title=:title, description=:description, min_people=:min_people, price=:price, stock=:stock,
                theme_id=:theme_id, diet_id=:diet_id
            WHERE id=:id
        ");
        $stmt->execute([
            ':id'          => $id,
            ':title'       => $data['title'],
            ':description' => $data['description'],
            ':min_people'  => (int)$data['min_people'],
            ':price'       => (float)$data['price'],
            ':stock'       => (int)$data['stock'],
            ':theme_id'    => !empty($data['theme_id']) ? (int)$data['theme_id'] : null,
            ':diet_id'     => !empty($data['diet_id']) ? (int)$data['diet_id'] : null,
        ]);
    }

    public static function delete(int $id): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM menus WHERE id=:id");
        $stmt->execute([':id' => $id]);
    }
}
