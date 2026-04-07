<?php

declare(strict_types=1);

namespace App\Services;

use App\Database\Database;
use App\Core\Session;
use ErrorException;
use Exception;
use PDOException;

class AuthService
{
    public function __construct(private Database $db)
    {
    }

    public function register(string $username, string $email, string $password): bool
    {

        try {
            $user = $this->db->query("INSERT INTO users(email,password_hash,username)
            VALUES (:email, :password, :username) 
            ON CONFLICT (username) DO NOTHING RETURNING user_id", [
                "username" => $username,
                "password" => password_hash($password, PASSWORD_BCRYPT),
                "email" => $email
            ])->fetch();

            if (!$user) {
                return false;
            }
        } catch (PDOException $e) {
            //! only works for psql cuz of hardcode of Code
            if ($e->getCode() === '23505' && str_contains($e->getMessage(), 'users_email_key')) {
                return false;
            }
            throw $e;
        }

        Session::elevate();
        Session::set('user_id', $user['user_id']);
        Session::set('username', $username);
        Session::set('email', $email);

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

        Session::elevate();
        Session::set('user_id', $user['user_id']);
        Session::set('username', $user['username']);
        Session::set('email', $user['email']);

        return true;
    }

    public function logout(): void
    {
        Session::destroy();
        header('Location: /');
        exit();
    }
}
