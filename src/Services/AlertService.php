<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use App\Models\Alert;

class AlertService
{
    // get all active alerts for product
    // for each alert:
    // if alert is "percent" - calculate the % of decrease.
    // if alert is absolute - calculate the summ of decrease.
    // if threshold is broken - send an notif
    // update the last_triggered_at

    public function __construct(private Alert $alert) {}

    public function check(array $product, float $old_price, float $new_price): void
    {
        $alerts = $this->alert->getAllActiveByProd($product['product_id']);
        foreach ($alerts as $alert) {
            $isTriggered = $this->isTriggered($alert, $old_price, $new_price);

            if ($isTriggered) {
                // todo Notify the user
                $this->alert->updateAlertTrigger($alert['alert_id']);
            }
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

        return match ($alert['type']) {
            'absolute' => $drop >= $alert['threshold_value'],
            'percent' => round(($drop / $old_price * 100), 2) >= $alert['threshold_value']
        };
    }
}
