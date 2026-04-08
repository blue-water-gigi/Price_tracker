<?php

declare(strict_types=1);

namespace App\Models;

use App\Database\Database;
use App\Services\Notifications\NotificationResult;

class NotificationLog
{
    public function __construct(private Database $db)
    {
    }
    public function create(
        array $alert,
        NotificationResult $result
    ): bool {
        return $this->db->query("INSERT INTO notification_logs (user_id,product_id,alert_id,notification_channel,message,status)
        VALUES(:user_id,:product_id,:alert_id,:notification_channel,:message,:status)", [
            'user_id' => $alert['user_id'],
            'product_id' => $alert['product_id'],
            'alert_id' => $alert['alert_id'],
            'notification_channel' => $result->notification_channel,
            'message' => $result->msg,
            'status' => $result->status
        ])->countRows() > 0;
    }
}