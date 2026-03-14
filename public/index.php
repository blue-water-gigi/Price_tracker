<?php

declare(strict_types=1);

require_once __DIR__ . "/../vendor/autoload.php";

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Services\Parsers\WbParser;
use Dotenv\Dotenv;
use App\Core\Router;
use App\Core\Session;

$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$parser = new WbParser();
$imgUrl = $parser->getImgUrl('518986553');
dd($imgUrl);


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

$router->dispatch();
