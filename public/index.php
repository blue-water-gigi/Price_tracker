<?php

declare(strict_types=1);

require_once __DIR__ . "/../vendor/autoload.php";

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\ProductController;
use App\Controllers\TgController;
use App\Database\Database;
use App\Services\Notifications\TgNotification;
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

//dashboard,settings
$router->get("/dashboard", [DashboardController::class, 'showDashboard']);
$router->get("/logout", [DashboardController::class, 'logout']);
$router->get('/dashboard/settings', [DashboardController::class, 'showSettings']);

//add-save-cancel
$router->get("/dashboard/add", [ProductController::class, 'showAddForm']);
$router->post("/dashboard/add", [ProductController::class, 'add']);
$router->post("/dashboard/save", [ProductController::class, 'save']);
$router->get("/dashboard/cancel", [ProductController::class, 'cancel']);

//delete-edit
$router->delete('/product/{id}', [ProductController::class, 'delete']);
$router->get('/product/{id}/edit', [ProductController::class, 'showEditForm']);
$router->patch('/product/{id}', [ProductController::class, 'update']);

//tg webhook (tg making POST request to server)
$router->post('/telegram/webhook', [TgController::class, 'handle']);

$router->dispatch();
