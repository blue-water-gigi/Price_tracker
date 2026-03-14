<?php

declare(strict_types=1);

namespace App\Controllers;

class DashboardController
{
    use Controller;
    public function showDashboard(): void
    {
        //todo change /register redirect to error redirect (403) later
        $this->requireAuth('/register');
        require_once self::basePath('views/dashboard/index.php');
    }
}
