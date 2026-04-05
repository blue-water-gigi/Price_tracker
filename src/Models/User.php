<?php

declare(strict_types=1);

namespace App\Models;

use App\Database\Database;
use Exception;

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

    public function linkCity(int $user_id, string $city): array
    {
        return $this->db->query("UPDATE users SET city = :city WHERE user_id = :user_id RETURNING *", [
            'city' => $city,
            'user_id' => $user_id
        ])->fetch() ?? [];
    }

    public function getUser(int $user_id): array
    {
        return $this->db->query("SELECT * FROM users WHERE user_id = :user_id", [
            'user_id' => $user_id
        ])->fetch() ?? [];
    }

    public function updateUsername(int $user_id, string $username): int
    {
        return $this->db->query("UPDATE users SET username = :username WHERE user_id = :user_id", [
            'user_id' => $user_id,
            'username' => $username
        ])->countRows() ?? 0;
    }

    public function saveLinkNonce(int $user_id, string $tg_link_nonce, int $tg_nonce_expires_at): bool
    {
        return $this->db->query("UPDATE users 
        SET tg_link_nonce = :tg_link_nonce, tg_nonce_expires_at = to_timestamp(:tg_nonce_expires_at)
        WHERE user_id = :user_id", [
            'user_id' => $user_id,
            'tg_link_nonce' => $tg_link_nonce,
            'tg_nonce_expires_at' => $tg_nonce_expires_at
        ])->countRows() > 0;
    }

    public function findByNounce(string $tg_link_nonce): ?array
    {
        return $this->db->query("SELECT user_id, tg_nonce_expires_at
        FROM users 
        WHERE tg_link_nonce = :tg_link_nonce", [
            'tg_link_nonce' => $tg_link_nonce
        ])->fetch() ?: null;
    }

    public function consumeLinkNonce(string $tg_link_nonce): bool
    {
        return $this->db->query("UPDATE users 
        SET tg_link_nonce = NULL, tg_nonce_expires_at = NULL 
        WHERE tg_link_nonce = :tg_link_nonce", [
            'tg_link_nonce' => $tg_link_nonce
        ])->countRows() > 0;
    }
}