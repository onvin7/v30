<?php
/**
 * web/index.php – front controller / router aplikace "Vodácká 30".
 *
 * Architektura: čisté PHP MVC (vlastní router, kontrolery, šablony) bez frameworku.
 * Stejný princip jako v referenčním projektu Cyklistickey:
 *   - mapa rout  path(regex) => [Controller::class, 'metoda']
 *   - shoda se hledá přes preg_match nad normalizovaným URI
 *   - kontroler na konci nastaví $view a vykreslí se přes app/Views/layout.php
 */

// Output buffering – umožní pozdější odeslání hlaviček (redirecty, flash zprávy).
if (!ob_get_level()) {
    ob_start();
}

// Session (pro budoucí formuláře / flash zprávy).
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Absolutní kořen projektu (o úroveň výš než složka /web).
define('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/config/autoloader.php';
require APP_ROOT . '/config/helpers.php';

use App\Controllers\AboutController;
use App\Controllers\ContactController;
use App\Controllers\GalleryController;
use App\Controllers\HomeController;
use App\Controllers\RaceController;
use App\Controllers\RegistrationController;
use App\Controllers\ResultsController;
use App\Controllers\StartListController;

// URI bez query stringu.
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($uri === false || $uri === null) {
    $uri = '/';
}

// Normalizace – odstranění koncového lomítka (kromě rootu "/").
if ($uri !== '/') {
    $uri = rtrim($uri, '/');
}

// HTTP metoda požadavku (GET / POST).
$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

// Mapa rout podle metody: metoda => [cesta (regex) => [třída kontroleru, metoda]].
$routes = [
    'GET' => [
        '/'                    => [HomeController::class, 'index'],
        '/zavod'               => [AboutController::class, 'index'],
        '/propozice'           => [RaceController::class, 'propositions'],
        '/prihlaska'           => [RegistrationController::class, 'create'],
        '/prihlaska/potvrzeni' => [RegistrationController::class, 'confirmation'],
        '/startovni-listina'   => [StartListController::class, 'index'],
        '/vysledky'            => [ResultsController::class, 'index'],
        '/galerie'             => [GalleryController::class, 'index'],
        '/kontakt'             => [ContactController::class, 'index'],
    ],
    'POST' => [
        '/prihlaska' => [RegistrationController::class, 'store'],
    ],
];

$routeFound = false;

// Nejdřív hledáme v mapě pro aktuální metodu.
foreach ($routes[$method] ?? [] as $path => $route) {
    if (preg_match('#^' . $path . '$#', $uri, $matches)) {
        $controllerClass = $route[0];
        $controllerMethod = $route[1];

        // Bez DB – kontrolery zatím nemají závislosti.
        $controller = new $controllerClass();
        $controller->$controllerMethod();

        $routeFound = true;
        break;
    }
}

// 405 – cesta existuje, ale pro jinou HTTP metodu.
if (!$routeFound) {
    foreach ($routes as $otherMethod => $otherRoutes) {
        if ($otherMethod === $method) {
            continue;
        }

        foreach ($otherRoutes as $path => $route) {
            if (preg_match('#^' . $path . '$#', $uri)) {
                http_response_code(405);
                header('Allow: ' . implode(', ', array_keys($routes)));
                $title = 'Metoda není povolena | Vodácká 30';
                $view  = APP_ROOT . '/app/Views/405.php';
                require APP_ROOT . '/app/Views/layout.php';
                exit;
            }
        }
    }
}

// 404 – na URI nesedla žádná routa.
if (!$routeFound) {
    http_response_code(404);
    $title = 'Stránka nenalezena | Vodácká 30';
    $view  = APP_ROOT . '/app/Views/404.php';
    require APP_ROOT . '/app/Views/layout.php';
    exit;
}
