<?php

declare(strict_types=1);

//!installed composer dump()
// function dd($value): never
// {
//     var_dump($value);
//     die();
// }

function convert(mixed $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}

function csrf(): string
{
    return '<input type="hidden" name="_csrf" value="' . App\Services\CsrfService::generate() . '">';
}