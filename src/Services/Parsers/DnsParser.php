<?php

declare(strict_types=1);

namespace App\Services\Parsers;

use Exception;

class DnsParser implements ParserInterface
{
    public function parse(string $url): array
    {
        return [];
    }
    //https://www.dns-shop.ru/product/e362da487a8e4571/aerogril-dexp-familyfry-daf-2208d-cernyj/

    //https://www.dns-shop.ru/actionMarketing/soft-bundle/get-bundles-by-product/?productId=e362da48-7a8e-4e23-9545-c61b65245715
    //https://www.dns-shop.ru/actionMarketing/soft-bundle/get-bundles-by-product/?productId=e362da48-7a8e-4e23-9545-c61b65245715
    //https://www.dns-shop.ru/pwa/pwa/get-product/?id=e362da48-7a8e-4e23-9545-c61b65245715
    //https://www.dns-shop.ru/product/microdata/e362da48-7a8e-4e23-9545-c61b65245715/

    // public function fetchJson(string $url): array
    // {
    //     $ch = curl_init($url);
    //     curl_setopt_array($ch, [
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_TIMEOUT => 10,
    //         CURLOPT_FOLLOWLOCATION => true,
    //         CURLOPT_HTTPHEADER => [
    //             'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    //             'Accept: application/json',
    //             'Referer: https://www.dns-shop.ru',
    //             'Origin: https://www.dns-shop.ru',
    //         ],
    //     ]);

    //     $response = curl_exec($ch);
    //     $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //     curl_close($ch);

    //     if ($response === false) {
    //         throw new Exception("cURL error: HTTP Code - {$httpCode}.");
    //     }

    //     $data = json_decode($response, true, 512, JSON_INVALID_UTF8_SUBSTITUTE);

    //     if (!$data) {
    //         $resp = file_put_contents('dns_response', $response);
    //         print_r($resp);
    //         throw new Exception("JSON error: " . json_last_error() . "." . json_last_error_msg());
    //     }

    //     return $data ?? [];
    // }
}
