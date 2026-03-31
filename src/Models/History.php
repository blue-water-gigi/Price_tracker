<?php

declare(strict_types=1);

namespace App\Models;

use App\Database\Database;

class History
{

    public function __construct(private Database $db)
    {
    }

    public function create(int $product_id, float $price): array
    {
        return $this->db->query("INSERT INTO price_history(price,product_id) VALUES (:price,:product_id) RETURNING *", [
            "price" => round($price, 2),
            "product_id" => $product_id
        ])->fetch();
    }

    public function getAllByProd(int $product_id): array
    {
        return $this->db->query("SELECT * FROM price_history WHERE product_id = :product_id ORDER BY checked_at ASC", [
            'product_id' => $product_id
        ])->fetchAll() ?? [];
    }

}
