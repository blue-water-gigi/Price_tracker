<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Session;

class CsrfService
{
    public static function generate(): string
    {
        if (Session::has('_csrf')) {
            return Session::get('_csrf');
        }

        $token = bin2hex(random_bytes(32));
        Session::set('_csrf', $token);

        return $token;
    }

    public static function verify(string $token): bool
    {
        $sesToken = Session::get('_csrf');
        if (!$sesToken) {
            return false;
        }
        return hash_equals($sesToken, $token);
    }
}