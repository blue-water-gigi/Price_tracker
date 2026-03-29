<?php

declare(strict_types=1);

namespace App\Services\Notifications;

use App\Services\EmailService;

class EmailNotification implements NotificationInterface
{

    public function __construct(private EmailService $emailService)
    {
    }

    public function sendMsg(array $alert, array $product, float $newPrice): bool
    {
        $email = $alert['email'];
        if (empty($email)) {
            return false;
        }

        $oldPrice = number_format((float) $product['current_price'], 0, '.', ' ');
        $newPrice = number_format($newPrice, 0, '.', ' ');

        $message = "🔔 Цена снизилась!<br>Товар: {$product['name']}"
            . "<br>Было: {$oldPrice} ₽"
            . "<br>Стало: {$newPrice} ₽"
            // . "<br>Снижение: {drop} ₽ / {percent}%"
            . "<br>🔗 <a href='{$product['url']}'>Ссылка на товар</a>";

        $this->emailService->sendMessage($email, $message);
        return true;
    }
}