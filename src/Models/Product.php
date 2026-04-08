<?php

declare(strict_types=1);

namespace App\Models;

use App\Database\Database;

class Product
{
    public function __construct(private Database $db)
    {
    }

    public function existsForUser(int $user_id, string $url): ?int
    {
        //todo add article validation (see .todo file)
        $product = $this->db->query("SELECT product_id FROM products WHERE user_id = :user_id AND url = :url", [
            "user_id" => $user_id,
            "url" => $url
        ])->fetch();

        return $product ? (int) $product['product_id'] : null;
    }

    public function findOrCreate(array $parsed, int $store_id, int $user_id, string $url): array
    {
        // find out if user already have this product followed
        $product_id = $this->existsForUser($user_id, $url);

        if ($product_id) {
            return $this->db->query("SELECT * FROM products WHERE product_id = :product_id", [
                "product_id" => $product_id
            ])->fetch();
        }

        //if not - insert into db and return inserted data
        return $this->db->query("INSERT INTO products(name,store_id,user_id,url,current_price,image_url)
        VALUES(:name, :store_id, :user_id, :url, :current_price, :image_url) RETURNING *", [
            "name" => $parsed["name"],
            "current_price" => $parsed["price"],
            "image_url" => $parsed["image_url"],
            "store_id" => $store_id,
            "user_id" => $user_id,
            "url" => $url,
        ])->fetch() ?? [];
    }

    public function getAllByUser(int $user_id): array
    {
        return $this->db->query("SELECT p.*, s.name AS store_name, prev.price AS previous_price
        FROM products AS p
        INNER JOIN stores AS s
        ON p.store_id = s.store_id
        LEFT JOIN LATERAL (
            SELECT price FROM price_history AS ph WHERE ph.product_id = p.product_id
            ORDER BY ph.checked_at DESC 
            LIMIT 1 OFFSET 1
        ) prev ON true
        WHERE p.user_id = :user_id AND p.is_active = TRUE 
        ORDER BY p.created_at ASC", [
            'user_id' => $user_id,
        ])->fetchAll() ?? [];
    }

    public function updatePrice(float $new_price, int $product_id): void
    {
        $this->db->query("UPDATE products 
        SET current_price = :new_price,updated_at = NOW() 
        WHERE product_id = :product_id", [
            "product_id" => $product_id,
            "new_price" => $new_price
        ]);
    }

    public function getAllActive(): array
    {
        return $this->db->query("SELECT p.*,s.parser_class,s.name AS store_name, u.city, u.email, u.telegram_chat_id
        FROM products AS p
        INNER JOIN stores AS s
        ON p.store_id = s.store_id
        INNER JOIN alerts AS a
        ON a.product_id = p.product_id
        INNER JOIN users AS u
        ON u.user_id = p.user_id
        WHERE p.is_active = TRUE
        AND a.is_active = TRUE
        AND (last_checked_at IS NULL OR last_checked_at + check_interval <= NOW())
        ")->fetchAll() ?? [];
    }

    public function deleteProduct(int $product_id, int $user_id): int
    {
        return $this->db->query("DELETE FROM products WHERE product_id = :product_id AND user_id = :user_id", [
            'product_id' => $product_id,
            'user_id' => $user_id
        ])->countRows();
    }

    public function getProduct(int $product_id, int $user_id): array
    {
        return $this->db->query("SELECT p.*,a.type,a.threshold_value,a.notification_channels,a.is_active,a.target_price,a.check_interval
        FROM products AS p 
        INNER JOIN alerts AS a 
        USING (product_id, user_id)
        WHERE product_id = :product_id AND user_id = :user_id", [
            'product_id' => $product_id,
            'user_id' => $user_id
        ])->fetch() ?: [];
    }
}
