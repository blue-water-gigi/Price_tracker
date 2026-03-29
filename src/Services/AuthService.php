<?php

declare(strict_types=1);

namespace App\Services;

use App\Database\Database;
use App\Core\Session;
use ErrorException;
use Exception;

class AuthService
{
    public function __construct(private Database $db) {}

    public function register(string $username, string $email, string $password): bool
    {
        try {
            $existUser = $this->db->query("SELECT * FROM users WHERE username = :username", [
                "username" => $username,
            ])->fetch();

            $existEmail = $this->db->query("SELECT * FROM users WHERE email = :email", [
                "email" => $email,
            ])->fetch();
        } catch (\Throwable $th) {
            throw new Exception("Error fetching data: " . $th->getMessage());
        }

        if ($existUser || $existEmail) {
            return false;
        }

        $this->db->query("INSERT INTO users(email,password_hash,username) VALUES (:email, :password, :username)", [
            "username" => $username,
            "password" => password_hash($password, PASSWORD_BCRYPT),
            "email" => $email
        ]);

        $userId = $this->db->getLastInsertId('users_user_id_seq');

        Session::start();
        Session::set('user_id', $userId);
        Session::set('username', $username);

        return true;
    }

    public function login(string $email, string $password): bool
    {
        $user = $this->db->query("SELECT * FROM users WHERE email = :email", [
            "email" => $email,
        ])->fetch();

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        Session::start();
        Session::set('user_id', $user['user_id']);
        Session::set('username', $user['username']);

        return true;
    }

    public function logout(): void
    {
        Session::destroy();
        //redirect to landing page
        header('Location: /');
        exit();
    }
}
