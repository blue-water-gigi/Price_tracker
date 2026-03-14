<?php

declare(strict_types=1);

namespace App\Services\Parsers;

interface ParserInterface
{
    public function parse(string $url): array;
}
