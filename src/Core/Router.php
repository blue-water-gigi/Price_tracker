<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array|callable $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array|callable $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        //find what handler is responsible for method-path combo
        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];

            //find out if handler is array or function
            if (is_callable($handler)) {
                $handler();
            } else if (is_array($handler)) {
                [$class, $method] = $handler;
                new $class()->$method();
            }
            return;
        }
        self::abort();
    }

    public function abort($code = 404)
    {
        http_response_code($code);
        //todo поменять на view 404,403 и т.д.
        echo "$code - Page not found.";
        exit();
    }
}
