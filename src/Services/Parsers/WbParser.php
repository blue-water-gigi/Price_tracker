<?php

declare(strict_types=1);

namespace App\Services\Parsers;

use Exception;

class WbParser implements ParserInterface
{
    public function parse(string $url): array
    {
        // extract the article
        $article = $this->extractArticle($url);

        //make request to API and parse json if fetch is valid
        //todo add dest variation for better user expirience.
        //? idea: when logged in - user have a popup (js) with request to choose city (default=moscow(1257786), input his choice here)
        $json = "https://card.wb.ru/cards/v4/detail?nm={$article}&curr=rub&dest=-1257786&appType=1&lang=ru";
        $fetched =  $this->fetchJson($json);

        $data = [];
        //return the array with data or empty
        $data['name'] = $fetched['products'][0]['name'] ?? "";
        $data['price'] = $fetched['products'][0]['sizes'][0]['price']['product'] ?? 0;
        $data['price'] /= 100;
        $data['image_url'] = $this->getImgUrl($article) ?? "no img";
        $data['currency'] = 'RUB';
        $data['extra']['article'] = $fetched['products'][0]['id'] ?? "";
        $data['extra']['brand'] = $fetched['products'][0]['brand'] ?? "";
        $data['extra']['productRating'] = $fetched['products'][0]['nmReviewRating'] ?? "";
        $data['extra']['suplierRating'] = $fetched['products'][0]['supplierRating'] ?? "";
        $data['extra']['priceBasic'] = $fetched['products'][0]['sizes'][0]['price']['basic'] ?? 0;
        $data['extra']['priceBasic'] /= 100;

        return $data ?? [];
    }

    private function fetchJson(string $url): array
    {

        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept: application/json',
                'Referer: https://www.wildberries.ru/',
                'Origin: https://www.wildberries.ru',
            ]
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($response === false || $httpCode !== 200) {
            throw new Exception("cURL error: HTTP Code - {$httpCode}.");
        }

        $data = json_decode($response, true, 512, JSON_INVALID_UTF8_SUBSTITUTE);

        if (!$data) {
            throw new Exception("JSON error: " . json_last_error() . "." . json_last_error_msg());
        }
        return $data ?? [];
    }

    private function extractArticle(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH) ?? '';

        if (preg_match('/\d+/', $path, $matches)) {
            return $matches[0];
        }
        return '';
    }

    private function getImgUrl(string $article, $offsets = [0, 1, -1, 2, -2, 3, -3, 4, -4, 5, -5]): ?string
    {
        $curr_art = (int) $article;

        $vol = (int) floor($curr_art / 100000);
        $part = (int) floor($curr_art / 1000);

        $basket = (int) $this->getBasket($vol);

        //if we get 404 from requst to url, then we trying to find it with offset +-5(default)
        foreach ($offsets as $offset) {
            $currBasketNum = $basket + $offset;

            if ($currBasketNum < 1) continue;

            $basketStr = str_pad((string)$currBasketNum, 2, '0', STR_PAD_LEFT);
            $url = "https://basket-{$basketStr}.wbbasket.ru/vol{$vol}/part{$part}/{$article}/images/big/1.webp";

            if ($this->isUrlExist($url)) {
                return $url;
            }
        }
        return null;
    }

    private function isUrlExist(string $url): bool
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_NOBODY => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 3,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Referer: https://www.wildberries.ru/',
                'Origin: https://www.wildberries.ru',
            ]
        ]);

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $status === 200;
    }

    private function getBasket(int $vol): string
    {
        // default basket system
        if ($vol <= 143) return '01';
        if ($vol <= 287) return '02';
        if ($vol <= 431) return '03';
        if ($vol <= 719) return '04';
        if ($vol <= 1007) return '05';
        if ($vol <= 1061) return '06';
        if ($vol <= 1115) return '07';
        if ($vol <= 1169) return '08';
        if ($vol <= 1313) return '09';
        if ($vol <= 1601) return '10';
        if ($vol <= 1655) return '11';
        if ($vol <= 1919) return '12';
        if ($vol <= 2045) return '13';

        $basketNum = (int)floor($vol / 324) + 12;

        return str_pad((string)$basketNum, 2, '0', STR_PAD_LEFT);
    }
}
