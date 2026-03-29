<?php

declare(strict_types=1);

namespace App\Services\Notifications;

interface NotificationInterface
{
    public function sendMsg(array $alert, array $product, float $newPrice): bool;
}
