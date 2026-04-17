<?php

declare(strict_types=1);

use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;

require_once __DIR__ . '/../bootstrap/app.php';

$routes = require __DIR__ . '/../routes/web.php';

$dispatcher = simpleDispatcher(function (FastRoute\RouteCollector $r) use ($routes) {
    foreach ($routes as [$method, $route, $handler]) {
        $r->addRoute($method, $route, $handler);
    }
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'] ?? '/';

if (false !== $position = strpos($uri, '?')) {
    $uri = substr($uri, 0, $position);
}

$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        http_response_code(404);
        require __DIR__ . '/../app/Views/errors/404.php';
        break;

    case Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo '405 - Método não permitido';
        break;

    case Dispatcher::FOUND:
        [$class, $method] = $routeInfo[1];
        $vars = $routeInfo[2];

        try {
            $controller = new $class();
            call_user_func([$controller, $method], $vars);
        } catch (Throwable $e) {
            http_response_code(500);

            if (config('APP_DEBUG', 'false') === 'true') {
                echo '<pre>' . htmlspecialchars($e->getMessage()) . "\n\n" . htmlspecialchars($e->getTraceAsString()) . '</pre>';
            } else {
                require __DIR__ . '/../app/Views/errors/500.php';
            }
        }

        break;
}
