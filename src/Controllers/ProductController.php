<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Session;
use App\Core\Validator;
use App\Models\Product;
use App\Models\Store;
use App\Database\Database;
use App\Models\History;
use App\Models\Alert;
use App\Services\Parsers\ParserFactory;
use App\Services\CreateTrackedProductService;
use Exception;
use InvalidArgumentException;
use PDOException;

class ProductController
{
    use Controller;

    private Validator $validator;
    private Product $product;
    private Store $store;
    private History $history;
    private Alert $alert;
    private CreateTrackedProductService $transaction;

    public function __construct()
    {
        //todo make DI container for Controllers
        $db = Database::getInstance();
        $this->validator = new Validator($_POST);
        $this->product = new Product($db);
        $this->store = new Store($db);
        $this->history = new History($db);
        $this->alert = new Alert($db);
        $this->transaction = new CreateTrackedProductService(
            $this->product,
            $this->store,
            $this->alert,
            $this->history,
            $db
        );
    }

    public function showAddForm(): void
    {
        $this->requireAuth('/login');
        $pending = Session::get('pending_product');
        if (!$pending) {
            $this->redirect('/dashboard');
        }

        $product = $pending['parsed'];
        require_once self::basePath("views/dashboard/add.php");
    }

    public function showEditForm(string $id): void
    {
        $this->requireAuth('/login');
        $user_id = (int) Session::get('user_id');
        $product = $this->product->getProduct((int) $id, $user_id);

        if (empty($product)) {
            $this->redirect('/dashboard');
        }

        require_once self::basePath("views/dashboard/edit.php");
    }

    public function showStats(string $id): void
    {
        $this->requireAuth('/login');
        $user_id = (int) Session::get('user_id');
        $product = $this->product->getProduct((int) $id, $user_id);
        $history = $this->history->getAllByProd((int) $id);

        if (empty($product) || empty($history)) {
            $this->redirect('/dashboard');
        }

        require_once self::basePath("views/dashboard/stats.php");
    }

    public function add(): void
    {
        $this->requireAuth('/login');

        $url = trim($_POST['url'] ?? '');
        $user_id = (int) Session::get('user_id');

        $validation = $this->validator
            ->required('url')
            ->url('url');

        if (!$validation->isValid()) {
            Session::flash('error', $validation->getErrors());
            Session::flash('old', $_POST);
            $this->redirect('/dashboard');
        }

        if ($this->product->existsForUser($user_id, $url)) {
            Session::flash('error', 'Товар уже отслеживается.');
            $this->redirect("/dashboard");
        }

        try {
            //todo bruh this is so fucking shit. Refactor that bitch
            $parsed = ParserFactory::make($url)->parse($url, ['city' => Session::get('city') ?? 'Москва и область']);
            Session::set("pending_product", [
                'parsed' => $parsed,
                'url' => $url
            ]);
            $this->redirect('/dashboard/add');
        } catch (InvalidArgumentException $e) {
            Session::flash('error', 'Магазин не поддерживается.');
            $this->redirect('/dashboard');
        } catch (Exception $e) {
            Session::flash('error', 'Ошибка при получении данных о товаре.');
            $this->redirect('/dashboard');
        }
    }
    public function save(): void
    {
        $this->requireAuth('/login');

        $user_id = (int) Session::get('user_id');
        $pending = Session::get('pending_product');
        if (!$pending) {
            $this->redirect('/dashboard');
        }

        try {
            $this->transaction->execute(
                $user_id,
                $pending['parsed'],
                $pending['url'],
                [
                    'alert_type' => $_POST['alert_type'] ?? 'absolute',
                    'threshold_value' => (float) ($_POST['threshold_value'] ?? 0),
                    'notify_channels' => $_POST['notify_channels'] ?? [],
                    'check_interval' => $_POST['check_interval'] ?? '60 minutes',
                    'target_price' => (float) ($_POST['target_price'] ?? 0)
                ]
            );
        } catch (Exception $e) {
            error_log('Transaction failed' . $e->getMessage());
            Session::flash('error', 'Ошибка при сохранении товара. Попробуйте снова.');
            $this->redirect('/dashboard/add');
        }

        Session::set('pending_product', null);
        Session::flash('success', 'Товар успешно добавлен!');
        $this->redirect("/dashboard");
    }

    public function cancel(): void
    {
        $this->requireAuth('/login');
        Session::set('pending_product', null);
        $this->redirect('/dashboard');
    }

    public function delete(string $id): void
    {
        $this->requireAuth('/login');

        $user_id = (int) Session::get('user_id');

        $deleted = $this->product->deleteProduct((int) $id, $user_id);
        if (!$deleted) {
            Session::flash('error', 'Товар не найден или нет доступа.');
            $this->redirect('/dashboard');
        }

        Session::flash('success', 'Товар успешно удалён!');
        $this->redirect('/dashboard');
    }

    public function update(string $id): void
    {
        $this->requireAuth('/login');

        $user_id = (int) Session::get('user_id');
        $alert_type = $_POST['alert_type'] ?? 'absolute';
        $threshold_value = (float) ($_POST['threshold_value'] ?? 0);
        $notif_channels = $_POST['notify_channels'] ?? [];
        $check_interval = $_POST['check_interval'] ?? '60 minutes';
        $target_price = (float) ($_POST['target_price'] ?? 0);

        $updated = $this->alert->updateAlerts(
            (int) $id,
            $user_id,
            $alert_type,
            $threshold_value,
            $notif_channels,
            $check_interval,
            $target_price
        );

        if (!$updated) {
            Session::flash('error', 'Произошла ошибка, заполните форму корректно');
        }

        Session::flash('success', 'Уведомления успешно обновлены!');
        $this->redirect('/dashboard');
    }
}
