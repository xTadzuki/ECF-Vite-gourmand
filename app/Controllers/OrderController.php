<?php
// app/Controllers/OrderController.php

require_once BASE_PATH . '/app/Core/Route.php';
require_once BASE_PATH . '/app/models/Menu.php';
require_once BASE_PATH . '/app/models/Order.php';
require_once BASE_PATH . '/app/models/UserProfile.php';
require_once BASE_PATH . '/app/Services/Mailer.php';
require_once BASE_PATH . '/app/Services/Auth.php';

class OrderController
{
    // Bordeaux = 0€ ; sinon 5€ + 0,59€/km
    private function computeDelivery(string $city, float $km): float
    {
        $city = mb_strtolower(trim($city));
        if ($city === 'bordeaux') return 0.0;
        return 5.0 + (0.59 * max(0, $km));
    }

    // -10% si nb personnes >= (min_people + 5)
    private function computeMenuPrice(float $basePrice, int $minPeople, int $peopleCount): float
    {
        $price = $basePrice;
        if ($peopleCount >= ($minPeople + 5)) {
            $price = $price * 0.90;
        }
        return $price;
    }

    public function create(): void
    {
        Auth::requireLogin();

        $menuId = isset($_GET['menu_id']) ? (int)$_GET['menu_id'] : 0;
        if ($menuId <= 0) {
            http_response_code(400);
            echo "menu_id manquant";
            return;
        }

        $menu = Menu::find($menuId);
        if (!$menu) {
            http_response_code(404);
            echo "Menu introuvable";
            return;
        }

        $user = UserProfile::find(Auth::userId());

        $errors = [];
        $success = false;
        $priceDetails = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $city        = trim($_POST['city'] ?? 'Bordeaux');
    $km          = (float)($_POST['km'] ?? 0);

    $eventDate   = trim($_POST['event_date'] ?? '');
    $eventTime   = trim($_POST['event_time'] ?? '');
    $peopleCount = (int)($_POST['people_count'] ?? 0);

    if ($eventDate === '') $errors[] = "Date obligatoire.";
    if ($eventTime === '') $errors[] = "Heure obligatoire.";
    if ($peopleCount <= 0) $errors[] = "Nombre de personnes invalide.";

    $minPeople = (int)($menu['min_people'] ?? 1);
    if ($peopleCount < $minPeople) {
        $errors[] = "Vous devez commander au minimum pour $minPeople personnes.";
    }

    $stock = (int)($menu['stock'] ?? 0);
    if ($stock <= 0) $errors[] = "Stock insuffisant pour ce menu.";

    if (empty($errors)) {

        // 1) Livraison
        $delivery = $this->computeDelivery($city, $km);

        // 2) Prix par personne (avec remise éventuelle)
        $basePricePerPerson = (float)($menu['price'] ?? 0);
        $finalPricePerPerson = $this->computeMenuPrice($basePricePerPerson, $minPeople, $peopleCount);

        // 3) Totaux 
        $baseTotalMenus  = $basePricePerPerson * $peopleCount;
        $finalTotalMenus = $finalPricePerPerson * $peopleCount;

        // 4) Discount total (en euros)
        $discountValue = max(0, $baseTotalMenus - $finalTotalMenus);

        // 5) Total final
        $total = $finalTotalMenus + $delivery;

        $priceDetails = [
            'menu_price_per_person' => $finalPricePerPerson,
            'menus_total'           => $finalTotalMenus,
            'delivery'              => $delivery,
            'discount'              => $discountValue,
            'total'                 => $total,
        ];

        // Stats Mongo (optionnel)
        require_once BASE_PATH . '/app/Services/MongoService.php';
        if (method_exists('MongoService', 'incrementMenu')) {
            MongoService::incrementMenu((int)$menu['id'], (string)$menu['title'], (float)$total);
        }

        // Création commande 
        $orderId = Order::create([
            'user_id'           => Auth::userId(),
            'menu_id'           => (int)$menuId,
            'event_date'        => $eventDate,
            'event_time'        => $eventTime,
            'people_count'      => $peopleCount,
            'delivery_city'     => $city,
            'delivery_distance' => $km,
            'delivery_price'    => $delivery,
            'discount'          => $discountValue,
            'total_price'       => $total,
            'status'            => 'créée',
        ]);

        Order::addStatusHistory($orderId, 'créée');
        Order::decreaseMenuStock($menuId);

        // Mail confirmation
        $mailer = new Mailer('log');
        $mailer->send(
            $user['email'],
            "Confirmation de votre commande",
            "Bonjour {$user['firstname']},\n\nVotre commande #$orderId a bien été enregistrée.\nMenu : {$menu['title']}\nPersonnes : $peopleCount\nTotal : " . number_format($total, 2, ',', ' ') . " €\n\nMerci !\nVite & Gourmand"
        );

        $success = true;
    }
}
        require_once BASE_PATH . '/app/Views/orders/create.php';
    }
}

}
