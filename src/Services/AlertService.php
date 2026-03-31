<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use App\Models\Alert;
use App\Services\Notifications\NotificationService;

class AlertService
{
    // get all active alerts for product
    // for each alert:
    // if alert is "percent" - calculate the % of decrease.
    // if alert is absolute - calculate the summ of decrease.
    // if threshold is broken - send an notif
    // update the last_triggered_at

    public function __construct(private Alert $alert, private NotificationService $notif)
    {
    }

    public function check(array $product, float $old_price, float $new_price): void
    {
        $alerts = $this->alert->getAllActiveByProd($product['product_id']);
        foreach ($alerts as $alert) {
            if ($this->isTriggered($alert, $old_price, $new_price)) {
                $this->notif->send($alert, $product, $new_price);
                $this->alert->updateAlertTrigger($alert['alert_id']);
            }
        }
    }

    public function markChecked(int $product_id): void
    {
        $alerts = $this->alert->getAllActiveByProd($product_id);

        foreach ($alerts as $alert) {
            //update last_checked_at anyway since we want user to chooce the interval themselfs
            $this->alert->updateLastChecked($alert['alert_id']);
        }
    }

    private function isTriggered(array $alert, float $old_price, float $new_price): bool
    {
        if ($old_price <= 0) {
            throw new Exception("old_price is zero.");
        }

        if ($old_price < $new_price) {
            return false;
        }

        $drop = (float) ($old_price - $new_price);

        $typeTrigger = match ($alert['type']) {
            'absolute' => $drop >= $alert['threshold_value'],
            'percent' => round(($drop / $old_price * 100), 2) >= $alert['threshold_value']
        };

        return $typeTrigger || $new_price <= $alert['target_price'];
    }
}
