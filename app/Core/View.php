<?php

declare(strict_types=1);

/**
 * Camada de renderização das views usando a engine Plates.
 *
 * Comentado para estudo: os comentários explicam o papel do arquivo e os pontos
 * principais do fluxo, sem alterar a lógica original da aplicação.
 */

namespace App\Core;

use League\Plates\Engine;

/**
 * Camada de renderização das views usando a engine Plates.
 */
class View
{
    private static ?Engine $engine = null;

    protected static function engine(): Engine
    {
        if (self::$engine === null) {
            self::$engine = new Engine(dirname(__DIR__) . '/Views');

            self::$engine->addData([
                'authUser' => auth(),
            ]);

            self::$engine->registerFunction('csrf_token', fn() => csrf_token());
            self::$engine->registerFunction('old', fn(string $key, mixed $default = '') => old($key, $default));
            self::$engine->registerFunction('flash', fn(string $key, mixed $default = null) => getFlash($key, $default));
        }

        return self::$engine;
    }

    public static function render(string $view, array $data = []): void
    {
        echo self::engine()->render($view, $data);
    }
}
