<?php

declare(strict_types=1);

/**
 * DEBUG
 * Mets à false en prod (Fly) si tu ne veux pas afficher les erreurs.
 * Tu peux aussi gérer ça via variable d'environnement.
 */
if (!defined('APP_DEBUG')) {
    define('APP_DEBUG', true);
}

if (APP_DEBUG) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
}

/**
 * BASE_PATH (doit être défini AVANT toute utilisation)
 */
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

/**
 * Autoload Composer (MongoDB Client, etc.)
 */
$autoload = BASE_PATH . '/vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
}

/*
|--------------------------------------------------------------------------
| Configuration de la session (AVANT session_start)
|--------------------------------------------------------------------------
*/
ini_set('session.cookie_httponly', '1');

/**
 * IMPORTANT : secure cookie uniquement si HTTPS est réellement actif.
 * Sur Fly/Docker, $_SERVER['HTTPS'] peut valoir "off" (non vide) -> piège.
 */
$isHttps = (
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (!empty($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443)
    || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
);

if ($isHttps) {
    ini_set('session.cookie_secure', '1');
}

ini_set('session.use_strict_mode', '1');

/*
|--------------------------------------------------------------------------
| Démarrage de la session (UNE SEULE FOIS)
|--------------------------------------------------------------------------
*/
session_start();

/*
|--------------------------------------------------------------------------
| Core
|--------------------------------------------------------------------------
*/
require_once BASE_PATH . '/app/Core/Route.php';

/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/
require_once BASE_PATH . '/app/Controllers/HomeController.php';
require_once BASE_PATH . '/app/Controllers/MenuController.php';
require_once BASE_PATH . '/app/Controllers/AuthController.php';
require_once BASE_PATH . '/app/Controllers/PasswordController.php';
require_once BASE_PATH . '/app/Controllers/OrderController.php';
require_once BASE_PATH . '/app/Controllers/UserController.php';
require_once BASE_PATH . '/app/Controllers/EmployeeController.php';
require_once BASE_PATH . '/app/Controllers/AdminController.php';
require_once BASE_PATH . '/app/Controllers/PageController.php';

/*
|--------------------------------------------------------------------------
| Router
|--------------------------------------------------------------------------
*/
$route = $_GET['r'] ?? Route::HOME;

switch ($route) {

    case Route::HOME:
        (new HomeController())->index();
        break;

    case Route::MENUS:
        (new MenuController())->index();
        break;

    case Route::MENU_SHOW:
        (new MenuController())->show();
        break;

    case Route::MENUS_JSON:
        (new MenuController())->json();
        break;

    case Route::CONTACT:
        (new PageController())->contact();
        break;

    case Route::MENTIONS:
        (new PageController())->mentions();
        break;

    case Route::CGV:
        (new PageController())->cgv();
        break;

    case Route::REGISTER:
        (new AuthController())->register();
        break;

    case Route::LOGIN:
        (new AuthController())->login();
        break;

    case Route::LOGOUT:
        (new AuthController())->logout();
        break;

    case Route::FORGOT:
        (new PasswordController())->forgot();
        break;

    case Route::RESET:
        (new PasswordController())->reset();
        break;

    case Route::ORDER_CREATE:
        (new OrderController())->create();
        break;

    case Route::USER_ORDERS:
        (new UserController())->dashboard();
        break;

    case Route::USER_ORDER_SHOW:
        (new UserController())->showOrder();
        break;

    case Route::USER_ORDER_EDIT:
        (new UserController())->editOrder();
        break;

    case Route::USER_ORDER_CANCEL:
        (new UserController())->cancelOrder();
        break;

    case Route::USER_PROFILE:
        (new UserController())->profile();
        break;

    case Route::EMPLOYEE:
        (new EmployeeController())->dashboard();
        break;

    case Route::EMPLOYEE_UPDATE:
        (new EmployeeController())->updateStatus();
        break;

    case Route::EMPLOYEE_CANCEL:
        (new EmployeeController())->cancel();
        break;

    case Route::EMPLOYEE_REVIEWS:
        (new EmployeeController())->reviews();
        break;

    case Route::EMPLOYEE_REVIEW_ACTION:
        (new EmployeeController())->reviewAction();
        break;

    case Route::ADMIN:
        (new AdminController())->dashboard();
        break;

    case Route::ADMIN_CREATE:
        (new AdminController())->createEmployee();
        break;

    case Route::ADMIN_TOGGLE:
        (new AdminController())->toggleEmployee();
        break;

    case Route::ADMIN_STATS_JSON:
        (new AdminController())->statsJson();
        break;

    case Route::ADMIN_MENUS:
        (new AdminController())->menus();
        break;

    case Route::ADMIN_MENU_CREATE:
        (new AdminController())->menuCreate();
        break;

    case Route::ADMIN_MENU_STORE:
        (new AdminController())->menuStore();
        break;

    case Route::ADMIN_MENU_EDIT:
        (new AdminController())->menuEdit();
        break;

    case Route::ADMIN_MENU_UPDATE:
        (new AdminController())->menuUpdate();
        break;

    case Route::ADMIN_MENU_DELETE:
        (new AdminController())->menuDelete();
        break;

    default:
        http_response_code(404);
        echo "404 - Page introuvable";
        break;
}
