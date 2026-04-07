<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Alert;
use App\Models\Product;
use App\Models\Store;
use App\Models\History;
use App\Database\Database;
use Exception;
use App\Core\Session;

class CreateTrackedProductService
{
    public function __construct(
        private Product $product,
        private Store $store,
        private Alert $alert,
        private History $history,
        private Database $db
    ) {
    }

    public function execute(int $user_id, array $parsed, string $url, array $alertData): void
    {
        $this->db->beginTransaction();

        try {
            $store = $this->store->findOrCreate($url);
            $product = $this->product->findOrCreate($parsed, $store['store_id'], $user_id, $url);
            $this->history->create($product['product_id'], (float) $product['current_price']);
            $this->alert->create(
                $user_id,
                $product['product_id'],
                $alertData['alert_type'],
                $alertData['threshold_value'],
                $alertData['notify_channels'],
                $alertData['check_interval'],
                $alertData['target_price']
            );

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollback();
            throw new \PDOException('Transaction failure. ' . $e->getMessage());
        }
    }
}