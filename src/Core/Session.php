<?php

declare(strict_types=1);

namespace App\Core;

class Session
{

    public static function start(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public static function configure(int $lifetime): void
    {
        session_set_cookie_params([
            'lifetime' => $lifetime,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
    }

    public static function elevate(): void
    {
        session_regenerate_id(true);
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
        // return (bool) static::get($key);
    }

    public static function destroy(): void
    {
        $_SESSION = [];
        session_destroy();

        setcookie('PHPSESSID', '', time() - 3600, '/');
    }

    public static function isLoggedIn(): bool
    {
        return static::has('user_id') && static::has('username');
    }

    public static function flash(string $key, mixed $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public static function getFlash(string $key, mixed $default = null): array|string|null
    {
        $value = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }
}
