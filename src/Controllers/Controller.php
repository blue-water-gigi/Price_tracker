<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Session;

trait Controller
{
    protected const BASE_PATH = __DIR__ . "/../../";

    public static function basePath(string $path): string
    {
        return self::BASE_PATH . $path;
    }

    public function redirect(string $path): never
    {
        header("Location: {$path}");
        exit();
    }

    public function requireAuth(string $path): void
    {
        Session::start();
        if (!Session::isLoggedIn()) {
            $this->redirect($path);
        }
    }
}
