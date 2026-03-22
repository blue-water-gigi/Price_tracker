<?php

declare(strict_types=1);

namespace App\Services\Parsers;

interface ParserInterface
{
    /**
     * Summary of parse
     * @param string $url
     * @return array {
     * name: string,
     * price: int | float,
     * image_url:string,
     * currency: string,
     * extra?: array
     * }
     */
    public function parse(string $url): array;
}
