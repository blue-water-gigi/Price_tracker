<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function compilePath(string $path): array
    {
        $route = preg_replace('/\{(\w+)\}/', '(\d+)', $path);
        $pattern = "#^{$route}\$#";

        preg_match_all('/\{(\w+)\}/', $path, $matches);

        return [
            'pattern' => $pattern,
            'params' => $matches[1]
        ];
    }

    public function addRoute(string $method, string $path, array|callable $handler): void
    {
        //destructurize compilePath
        ['pattern' => $pattern, 'params' => $params] = $this->compilePath($path);

        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'params' => $params,
            'handler' => $handler,
        ];
    }

    public function get(string $path, array|callable $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, array|callable $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            if (!preg_match($route['pattern'], $path, $matches)) {
                continue;
            }

            array_shift($matches);
            $params = !empty($route['params'])
                ? array_combine($route['params'], $matches)
                : [];

            $handler = $route['handler'];

            if (is_callable($handler)) {
                $handler(...array_values($params));
            } else {
                [$class, $action] = $handler;
                (new $class())->$action(...array_values($params));
            }
            return;
        }
        $this->abort();
    }

    public function abort($code = 404)
    {
        http_response_code($code);
        $view = match ($code) {
            403 => 'errors/403.php',
            404 => 'errors/404.php',
            default => 'errors/404.php'
        };
        require_once __DIR__ . "/../../views/{$view}";
        exit();
    }
}
