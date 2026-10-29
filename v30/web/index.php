<?php
/**
 * web/index.php – front controller / router aplikace "Vodácká 30".
 *
 * Architektura: čisté PHP MVC (vlastní router, kontrolery, šablony) bez frameworku.
 * Stejný princip jako v referenčním projektu Cyklistickey:
 *   - mapa rout  path(regex) => [Controller::class, 'metoda', (nepovinná nastavení)]
 *   - shoda se hledá přes preg_match nad normalizovaným URI
 *   - kontroler na konci nastaví $view a vykreslí se přes app/Views/layout.php
 */

// Output buffering – umožní pozdější odeslání hlaviček (redirecty, flash zprávy).
if (!ob_get_level()) {
    ob_start();
}

// Session (pro formuláře, flash zprávy a přihlášení do administrace).
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
use App\Controllers\SearchController;
use App\Controllers\StartListController;
use App\Helpers\Seo;

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

// --- Generované soubory pro vyhledávače -----------------------------------

// sitemap.xml
if ($method === 'GET' && $uri === '/sitemap.xml') {
    header('Content-Type: application/xml; charset=utf-8');
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
    foreach (Seo::sitemapUrls() as $polozka) {
        echo "  <url>\n";
        echo '    <loc>' . htmlspecialchars($polozka['loc'], ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</loc>\n";
        echo '    <changefreq>' . $polozka['changefreq'] . "</changefreq>\n";
        echo '    <priority>' . $polozka['priority'] . "</priority>\n";
        echo "  </url>\n";
    }
    echo "</urlset>\n";
    exit;
}

// robots.txt
if ($method === 'GET' && $uri === '/robots.txt') {
    header('Content-Type: text/plain; charset=utf-8');
    echo "User-agent: *\n";
    echo "Allow: /\n";
    echo "Disallow: /admin\n";
    echo "Disallow: /oddil/oznameni/\n\n";
    echo 'Sitemap: ' . Seo::baseUrl() . "/sitemap.xml\n";
    exit;
}

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
        '/zavodnici'           => [SearchController::class, 'index'],
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
        $controllerClass  = $route[0];
        $controllerMethod = $route[1];

        // Bez DB – kontrolery zatím nemají závislosti.
        $controller = new $controllerClass();
        $controller->$controllerMethod(...array_slice($matches, 1));

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
                // Hlavička Allow smí uvádět jen metody, které na dané cestě opravdu existují.
                $povoleneMetody = [];
                foreach ($routes as $metoda => $metodyRouty) {
                    foreach ($metodyRouty as $cesta => $routa) {
                        if (preg_match('#^' . $cesta . '$#', $uri)) {
                            $povoleneMetody[] = $metoda;
                            break;
                        }
                    }
                }

                http_response_code(405);
                header('Allow: ' . implode(', ', $povoleneMetody));
                $title       = 'Metoda není povolena | Vodácká 30';
                $description = 'Požadovanou stránku nelze načíst touto metodou.';
                $robots      = false;
                $view        = APP_ROOT . '/app/Views/405.php';
                require APP_ROOT . '/app/Views/layout.php';
                exit;
            }
        }
    }
}

// 404 – na URI nesedla žádná routa.
if (!$routeFound) {
    http_response_code(404);
    $title       = 'Stránka nenalezena | Vodácká 30';
    $description = 'Hledaná stránka na webu Vodácká 30 neexistuje.';
    $robots      = false;
    $view        = APP_ROOT . '/app/Views/404.php';
    require APP_ROOT . '/app/Views/layout.php';
    exit;
}
