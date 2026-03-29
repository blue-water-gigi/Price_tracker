<?php

declare(strict_types=1);

use App\Services\EmailService;
use App\Services\Notifications\TgNotification;
use App\Services\TgService;
use App\Models\Alert;
use App\Models\History;
use Dotenv\Dotenv;
use App\Database\Database;
use App\Models\Product;
use App\Models\User;
use App\Services\AlertService;
use App\Services\Notifications\EmailNotification;
use App\Services\Notifications\NotificationService;
use App\Services\PriceCheckService;

require_once __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('Europe/Moscow');

$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$db = Database::getInstance();

//Models
$product = new Product($db);
$alert = new Alert($db);
$history = new History($db);
$user = new User($db);

//Services
$tgService = new TgService($_ENV['TG_BOT_TOKEN']);
$emailService = new EmailService([
    'host' => $_ENV['MAIL_HOST'],
    'port' => $_ENV['MAIL_PORT'],
    'auth' => $_ENV['MAIL_AUTH'] ?? null,
    'username' => $_ENV['MAIL_USERNAME'] ?? null,
    'password' => $_ENV['MAIL_PASSWORD'] ?? null,
    'fromEmail' => $_ENV['MAIL_ADDRESS'],
    'fromName' => $_ENV['MAIL_NAME'],
    'encryption' => $_ENV['MAIL_ENCRYPTION'] ?? '',
    'debug' => (int) ($_ENV['MAIL_SMTP_DEBUG'] ?? 0),
]);
$notif = new NotificationService([
    'telegram' => new TgNotification($tgService),
    'email' => new EmailNotification($emailService),
    // 'sms' => new SmsNotification($smsService)
]);
$alertService = new AlertService($alert, $notif);
$service = new PriceCheckService($product, $alertService, $history);

//run check
$result = $service->run();

//output
$timestamp = date('Y-m-d H:i:s');
echo "[{$timestamp}] Price check completed.\n";
echo "--- Stats\nTotal: {$result['total']} | Updated: {$result['updated']} | Errors: {$result['errors']} | No changes: {$result['no_change']}\n";
echo "-------------------------------------------------------------------\n";
