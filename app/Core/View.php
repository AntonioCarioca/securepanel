<?php

declare(strict_types=1);

namespace App\Core;

use League\Plates\Engine;

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
