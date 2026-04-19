<?php

declare(strict_types=1);

use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;

require_once __DIR__ . '/../bootstrap/app.php';

$webRoutes = require __DIR__ . '/../routes/web.php';
$apiRoutes = require __DIR__ . '/../routes/api.php';

$routes = array_merge($webRoutes, $apiRoutes);

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

        if (str_starts_with($uri, '/api/')) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'message' => 'Rota não encontrada.',
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            break;
        }

        \App\Core\View::render('errors/404');
        break;

    case Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);

        if (str_starts_with($uri, '/api/')) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'message' => 'Método não permitido.',
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            break;
        }

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

            if (str_starts_with($uri, '/api/')) {
                header('Content-Type: application/json; charset=utf-8');

                $response = [
                    'success' => false,
                    'message' => 'Erro interno do servidor.',
                ];

                if (config('APP_DEBUG', 'false') === 'true') {
                    $response['error'] = $e->getMessage();
                }

                echo json_encode(
                    $response,
                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
                );
                break;
            }

            if (config('APP_DEBUG', 'false') === 'true') {
                echo '<pre>' . htmlspecialchars($e->getMessage()) . "\n\n" . htmlspecialchars($e->getTraceAsString()) . '</pre>';
            } else {
                \App\Core\View::render('errors/500');
            }
        }

        break;
}
