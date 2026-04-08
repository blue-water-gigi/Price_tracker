<?php

declare(strict_types=1);

namespace App\Services\Notifications;

use App\Services\TgService;
use Exception;

class TgNotification implements NotificationInterface
{
    public function __construct(private TgService $tgService)
    {
    }

    public function sendMsg(array $alert, array $product, float $newPrice): NotificationResult
    {
        $tg_chat_id = $alert['telegram_chat_id'] ?? null;
        if (!$tg_chat_id) {
            return new NotificationResult('telegram', 'error', 'tg_chat_id is null');
        }

        $oldPrice = number_format((float) $product['current_price'], 0, '.', ' ');
        $newPrice = number_format($newPrice, 0, '.', ' ');

        $message = "🔔 Цена снизилась!\nТовар: {$product['name']}"
            . "\nБыло: {$oldPrice} ₽"
            . "\nСтало: {$newPrice} ₽"
            // . "\nСнижение: {drop} ₽ / {percent}%\n"
            . "\n🔗 <a href='{$product['url']}'>Ссылка на товар</a>";

        try {
            $this->tgService->sendMessage($tg_chat_id, $message);
            return new NotificationResult('telegram', 'success', "Notification send on id={$tg_chat_id}");
        } catch (Exception $e) {
            return new NotificationResult('telegram', 'error', $e->getMessage());
        }
    }
}
