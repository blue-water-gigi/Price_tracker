<?php

declare(strict_types=1);

namespace App\Services\Parsers;

use InvalidArgumentException;

class ParserFactory
{

    public static function make(string $url): ParserInterface
    {
        $domen = parse_url($url, PHP_URL_HOST) ?? "";
        return match (true) {
            str_contains($domen, 'ozon') => throw new InvalidArgumentException('Ozon is though. Really good anti-bot deffence. Unsupported market at the moment.'),
            str_contains($domen, 'market.yandex') => throw new InvalidArgumentException('Market.yandex is though. Really good anti-bot deffence.Unsupported market at the moment.'),
            str_contains($domen, 'megamarket') => throw new InvalidArgumentException('Megamarket is though. Really good anti-bot deffence.Unsupported market at the moment.'),
            str_contains($domen, 'wildberries') => new WbParser(),
            default => throw new InvalidArgumentException("Unsuported market: {$domen}")
        };
    }
}
