<?php

declare(strict_types=1);

namespace App\Models;

use app\Database\Database;

class Store
{
    private const array STORE_MAP = [
        'wildberries' => 'WbParser',
        'telegram' => 'telegram',
        'tg' => 'telegram'
    ];

    public function __construct(private Database $db) {}

    private function extractName(string $url): string
    {
        //todo add validator method that verifies that $url is not empty and etc.
        if (empty($url)) {
            throw new \DomainException("Invalid domnain. url provided is empty.");
        }

        //if theres no https or http before - we should add it so parse_url would work;
        if (!str_starts_with($url, "https") && !str_starts_with($url, "http")) {
            $url = "https://{$url}";
        }
        $domen = parse_url($url, PHP_URL_HOST) ?? '';
        $host = preg_replace("/^www\./", "", $domen);
        return explode(".", $host)[0];
    }

    public function findOrCreate(string $url): array
    {
        $storeName = $this->extractName($url);

        //get store info
        $storeInfo = $this->db->query("SELECT * FROM stores WHERE name = :name", [
            "name" => $storeName
        ])->fetch();

        if ($storeInfo) {
            return $storeInfo;
        }

        // if empty - create
        $parser_class = self::STORE_MAP[$storeName] ?? null;

        if (!$parser_class) {
            throw new \DomainException("Invalid domnain. Unsuported store - {$storeName}");
        }

        return $this->db->query("INSERT INTO stores(name,parser_class) VALUES (:name, :parser_class) RETURNING *", [
            "name" => $storeName,
            "parser_class" => $parser_class,
        ])->fetch() ?? [];
    }
}
