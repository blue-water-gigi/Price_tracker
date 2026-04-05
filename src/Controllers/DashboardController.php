<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Session;
use App\Models\Product;
use App\Models\User;
use App\Database\Database;
use App\Services\TgService;

class DashboardController
{
    use Controller;
    public function showDashboard(): void
    {
        //todo change /register redirect to error redirect (403) later
        $this->requireAuth('/login');
        $user_id = (int) Session::get('user_id');
        $product = new Product(Database::getInstance());
        $products = $product->getAllByUser($user_id);
        require_once self::basePath('views/dashboard/index.php');
    }

    public function logout(): void
    {
        $this->requireAuth("/login");
        Session::destroy();
        $this->redirect('/');
    }

    public function showSettings(): void
    {
        $this->requireAuth('/login');
        $user_id = (int) Session::get('user_id');

        $userModel = new User(Database::getInstance());
        $tgService = new TgService($_ENV['TG_BOT_TOKEN']);

        $user = $userModel->getUser($user_id);

        //for default = null, if already linked
        $token = null;
        if (empty($user['telegram_chat_id'])) {
            $token = $tgService->generateLinkToken($user_id);
            $nonce = substr($token, 0, 32);
            $userModel->saveLinkNonce($user_id, $nonce, time() + 300);
        }

        require_once self::basePath('views/dashboard/settings.php');
    }

    public function saveCity(): void
    {
        $this->requireAuth('/login');
        $user_id = (int) Session::get('user_id');
        $user = (new User(Database::getInstance()))->linkCity($user_id, $_POST['city']);
        Session::set('city', $_POST['city'] ?? 'Москва и область');
        $this->redirect('/dashboard');
    }
}
