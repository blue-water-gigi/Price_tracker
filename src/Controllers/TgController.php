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

        $token = $parts[1];
        if (strlen($token) !== 48) {
            $this->tgService->sendMessage($chat_id, "<b>❌ Ошибка!</b>\nНедействительный токен.");
            return;
        }

        $nonce = substr($token, 0, 32);

        $userData = $this->user->findByNounce($nonce);

        if (!$userData) {
            $this->tgService->sendMessage($chat_id, "<b>❌ Ошибка!</b>\nТокен не найден или уже использован.");
            return;
        }

        if (strtotime($userData['tg_nonce_expires_at']) < time()) {
            $this->user->consumeLinkNonce($nonce);
            $this->tgService->sendMessage($chat_id, "<b>❌ Ошибка!</b>\nТокен истёк. Вернитесь на сайт и попробуйте снова.");
            return;
        }

        if (!$this->tgService->verifyLinkToken($token, (string) $userData['user_id'])) {
            $this->tgService->sendMessage($chat_id, "<b>❌ Ошибка!</b>\nПодпись токена неверна.");
            return;
        }

        $this->user->consumeLinkNonce($nonce);

        $isLinked = $this->user->linkTg((int) $userData['user_id'], $chat_id);

        if ($isLinked) {
            $this->tgService->sendMessage($chat_id, "<b>✔ Успешно!</b>\nВаш аккаунт привязан. Теперь я буду присылать уведомления о ценах сюда.");
        } else {
            $this->tgService->sendMessage($chat_id, "<b>❌ Ошибка!</b>\nАккаунт уже привязан. Уведомления будут приходить сюда 😉");
        }
    }
}
