<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use App\Services\AuthService;
use App\Database\Database;
use App\Core\Validator;
use App\Core\Session;

class AuthController
{
    use Controller;
    private AuthService $authService;
    private Validator $validator;

    public function __construct()
    {
        //todo make DI container for Controllers
        $this->authService = new AuthService(db: Database::getInstance());
        $this->validator = new Validator($_POST);
    }

    public function showLanding(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        require self::basePath('/views/landing.php');
    }
    public function showRegister(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        require self::basePath('/views/auth/register.php');
    }
    public function showLogin(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        require self::basePath('/views/auth/login.php');
    }
    public function register(): void
    {
        $validation = $this->validator
            ->email('email')
            ->required('email')
            ->length('email', 1, 50)
            ->required('username')
            ->length('username', 2, 70)
            ->required('password')
            ->length('password', 4, 40)
            ->confirmPass();

        if (!$validation->isValid()) {
            Session::flash('errors', $validation->getErrors());
            Session::flash('old', $_POST);
            $this->redirect('/register');
        }

        $authentification = $this->authService->register(
            $_POST['username'],
            $_POST['email'],
            $_POST['password']
        );

        if (!$authentification) {
            Session::flash('errors', 'Email или username заняты');
            $this->redirect('/register');
        }
        $this->redirect('/dashboard');
    }

    public function login(): void
    {
        $validation = $this->validator
            ->required('email')
            ->required('password');

        if (!$validation->isValid()) {
            Session::flash('errors', $validation->getErrors());
            $this->redirect('/login');
        }

        $authentification = $this->authService->login(
            $_POST['email'],
            $_POST['password']
        );

        if (!$authentification) {
            Session::flash('errors', [
                'auth' => ['Неверный email или password.']
            ]);
            Session::flash('old', $_POST);
            $this->redirect('/login');
        }
        $this->redirect('/dashboard');
    }

    public function updateUsername(): void
    {
        $this->requireAuth('/login');
        $username = trim((string) ($_POST['username'] ?? ''));
        $validation = (new Validator(['username' => $username]))
            ->required('username')
            ->length('username', 2, 70);

        if (!$validation->isValid()) {
            Session::flash('errors', $validation->getErrors());
            Session::flash('old', $_POST);
            $this->redirect('/dashboard/settings');
        }

        $user_id = (int) Session::get('user_id');
        $rows = (new User(Database::getInstance()))->updateUsername($user_id, $username);
        if ($rows > 0) {
            Session::set('username', $username);
        }
        $this->redirect('/dashboard');
    }
}
