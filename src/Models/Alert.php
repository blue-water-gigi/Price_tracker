<?php

declare(strict_types=1);

namespace App\Models;

use App\Database\Database;

class Alert
{

    public function __construct(private Database $db) {}

    public function create(
        int $user_id,
        int $product_id,
        string $alert_type,
        float $threshold_value,
        array $notif_channels,
        string $check_interval
    ): array {
        return $this->db->query("INSERT INTO alerts(
        user_id,product_id,type,threshold_value,notification_channels,check_interval)
        VALUES (:user_id,:product_id,:type,:threshold_value,:notification_channels,:check_interval)
        RETURNING *", [
            'user_id' => $user_id,
            'product_id' => $product_id,
            'type' => $alert_type,
            'threshold_value' => round($threshold_value, 2),
            'notification_channels' => json_encode($notif_channels),
            'check_interval' => $check_interval
        ])->fetch();
    }

    public function getAllActiveByProd(int $product_id): array
    {
        return $this->db->query("SELECT a.*,u.email,u.telegram_chat_id,u.phone
         FROM alerts AS a 
         INNER JOIN users AS u 
         ON a.user_id = u.user_id
         WHERE product_id = :product_id", [
            "product_id" => $product_id
        ])->fetchAll() ?? [];
    }

    public function updateAlertTrigger(int $alert_id): void
    {
        $this->db->query("UPDATE alerts SET last_triggered_at = NOW() WHERE alert_id = :alert_id", [
            "alert_id" => $alert_id
        ]);
    }
}
