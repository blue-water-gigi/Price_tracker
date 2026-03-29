<?php

declare(strict_types=1);

namespace App\Services\Notifications;

use App\Services\TgService;

class NotificationService
{
    public function __construct(private array $channels)
    {
    }

    public function send(array $alert, array $product, float $newPrice): void
    {
        $selectedChannels = json_decode($alert['notification_channels'], true, 512);

        foreach ($selectedChannels as $channel) {
            if (isset($this->channels[$channel])) {
                //realisation in check_prices.php
                $this->channels[$channel]->sendMsg($alert, $product, $newPrice);
            }
        }
    }
}
