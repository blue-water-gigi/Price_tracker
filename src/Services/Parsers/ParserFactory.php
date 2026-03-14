<?php

declare(strict_types=1);

namespace App\Services\Parsers;

use InvalidArgumentException;

class ParserFactory
{

    public static function make(string $url): ParserInterface
    {
        try {
            $domen = parse_url($url, PHP_URL_HOST);

            if (str_contains($domen, 'ozon')) {
                return new OzonParser();
            } else if (str_contains($domen, 'wildberries')) {
                return new OzonParser();
            } else if (str_contains($domen, 'yandex.market')) {
                return new YandexParser();
            }
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException("Unsuported market: {$domen}" . $e->getMessage());
        }
    }
}
