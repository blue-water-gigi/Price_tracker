<?php

declare(strict_types=1);

namespace App\Services\Notifications;

use App\Services\EmailService;
use Exception;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class EmailNotification implements NotificationInterface
{

    public function __construct(private EmailService $emailService)
    {
    }

    public function sendMsg(array $alert, array $product, float $newPrice): NotificationResult
    {
        $email = $alert['email'] ?: null;
        if (empty($email)) {
            return new NotificationResult('email', 'error', 'email is null or empty');
        }

        $oldPrice = number_format((float) $product['current_price'], 0, '.', ' ');
        $newPrice = number_format($newPrice, 0, '.', ' ');

        $message = "🔔 Цена снизилась!<br>Товар: {$product['name']}"
            . "<br>Было: {$oldPrice} ₽"
            . "<br>Стало: {$newPrice} ₽"
            // . "<br>Снижение: {drop} ₽ / {percent}%"
            . "<br>🔗 <a href='{$product['url']}'>Ссылка на товар</a>";

        try {
            $this->emailService->sendMessage($email, $message);
            return new NotificationResult('email', 'success', "Notification send on email={$email}");
        } catch (PHPMailerException $e) {
            return new NotificationResult('email', 'error', $e->getMessage());
        }

    }
}