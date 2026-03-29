<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Database\Database;
use App\Models\User;
use App\Services\TgService;

class TgController
{
    use Controller;

    private TgService $tgService;
    private User $user;

    public function __construct()
    {
        $this->user = new User(Database::getInstance());
        $this->tgService = new TgService($_ENV['TG_BOT_TOKEN']);
    }

    public function handle(): void
    {
        try {
            $this->handleWebhook();
        } catch (\Exception $th) {
            error_log('Telegram webhook error: ' . $th->getMessage());
        }
    }

    private function getWebHookData(): array|bool
    {

        $input = file_get_contents('php://input');

        if (!$input) {
            throw new \BadFunctionCallException('Failed to get content.');
        }
        $update = json_decode($input, true, 512, JSON_THROW_ON_ERROR) ?? [];
        if (!$update) {
            throw new \JsonException('Json fetching error. ' . json_last_error_msg());
        }
        return $update;
    }

    private function handleWebhook(): void
    {
        $update = $this->getWebHookData();

        if (!isset($update)) {
            return;
        }

        $text = $update['message']['text'] ?? null;
        $chat_id = $update['message']['chat']['id'] ?? null;

        if (!$text || !$chat_id) {
            return;
        }

        if (str_starts_with($text, '/start')) {
            $this->processStartCommand($text, $chat_id);
        }
    }

    private function processStartCommand(string $text, int $chat_id): void
    {
        $parts = explode(' ', $text);

        if (count($parts) < 2) {
            $this->tgService->sendMessage($chat_id, convert('Пожалуйста, используйте ссылку из личного кабинета.'));
            return;
        }

        $user_id = (int) base64_decode($parts[1]);

        $isLinked = $this->user->linkTg($user_id, $chat_id);

        if ($isLinked) {
            $this->tgService->sendMessage($chat_id, "<b>✔ Успешно!</b>\nВаш аккаунт привязан. Теперь я буду присылать уведомления о ценах сюда.");
        } else {
            $this->tgService->sendMessage($chat_id, "<b>❌ Ошибка!</b>\nАккаунт уже привязан. Уведомления будут приходить сюда 😉");
        }
    }
}
