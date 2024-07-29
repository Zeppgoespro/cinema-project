<?php

declare(strict_types=1);

use App\App;
use App\Config;
use App\Controllers\MainController;
use App\Controllers\MovieController;
use App\Router;


/* Настройка среды */

require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

define('VIEW_PATH', __DIR__ . '/../views'); // Путь к файлам представлений

$router = new Router();

/* Конец настройки среды */


/* Маршруты */

$router->get('/', [MainController::class, 'index']); // View
$router->get('/movies', [MovieController::class, 'index']); // View
$router->get('/movies/{id}', [MovieController::class, 'show']); // View
$router->post('/movies', [MovieController::class, 'store']); // POST
$router->put('/movies/{id}', [MovieController::class, 'update']); // AJAX-JSON
$router->delete('/movies/{id}', [MovieController::class, 'delete']); // AJAX-JSON

/* Конец маршрутов */


/* Бутстрапер */

(new App(
    $router,
    ['uri' => $_SERVER['REQUEST_URI'], 'method' => $_SERVER['REQUEST_METHOD']],
    new Config($_ENV))
)->run();

/* Конец бутстрапера */
