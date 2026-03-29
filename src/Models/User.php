<?php

declare(strict_types=1);

namespace App\Models;

use App\Database\Database;

class User
{

    public function __construct(private Database $db)
    {
    }

    public function linkTg(int $user_id, int $telegram_chat_id): int
    {
        if ($this->isIdTaken($user_id, $telegram_chat_id))
            return 0;

        return $this->db->query("UPDATE users SET telegram_chat_id = :telegram_chat_id WHERE user_id = :user_id", [
            'telegram_chat_id' => $telegram_chat_id,
            'user_id' => $user_id
        ])->countRows();
    }

    private function isIdTaken(int $user_id, int $telegram_chat_id): bool
    {
        $isTaken = $this->db->query("SELECT user_id FROM users WHERE telegram_chat_id = :telegram_chat_id", [
            'telegram_chat_id' => $telegram_chat_id
        ])->fetch();

        if ($isTaken) {
            return true;
        }
        return false;
    }

    public function getTgChatId(int $user_id): ?array
    {
        return $this->db->query("SELECT telegram_chat_id FROM users WHERE user_id = :user_id", [
            'user_id' => $user_id
        ])->fetch() ?? null;
    }

    public function getEmail(int $user_id): ?string
    {
        return $this->db->query("SELECT email FROM users WHERE user_id = :user_id", [
            'user_id' => $user_id
        ])->fetch() ?? null;
    }
}