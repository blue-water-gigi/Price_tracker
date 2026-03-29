<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Session;
use App\Models\Product;
use App\Models\User;
use App\Database\Database;

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
        $tg_chat_id = new User(Database::getInstance())->getTgChatId($user_id);
        require_once self::basePath('views/dashboard/settings.php');
    }
}
