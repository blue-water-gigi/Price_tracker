<?php

declare(strict_types=1);

require_once __DIR__ . "/../vendor/autoload.php";

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\ProductController;
use Dotenv\Dotenv;
use App\Core\Router;
use App\Core\Session;

$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

Session::start();

$router = new Router();

//auth and register
$router->get("/", [AuthController::class, 'showLanding']);
$router->get("/register", [AuthController::class, 'showRegister']);
$router->get("/login", [AuthController::class, 'showLogin']);
$router->post("/register", [AuthController::class, 'register']);
$router->post("/login", [AuthController::class, 'login']);

//dashboard and main
$router->get("/dashboard", [DashboardController::class, 'showDashboard']);
$router->get("/logout", [DashboardController::class, 'logout']);

//add-save-cancel
$router->get("/dashboard/add", [ProductController::class, 'showAddForm']);
$router->post("/dashboard/add", [ProductController::class, 'add']);
$router->post("/dashboard/save", [ProductController::class, 'save']);
$router->get("/dashboard/cancel", [ProductController::class, 'cancel']);

$router->dispatch();
