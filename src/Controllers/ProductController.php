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
use Exception;
use InvalidArgumentException;

class ProductController
{
    use Controller;

    private Validator $validator;
    private Product $product;
    private Store $store;
    private History $history;
    private Alert $alert;

    public function __construct()
    {
        //todo make DI container for Controllers
        $this->validator = new Validator($_POST);
        $this->product = new Product(Database::getInstance());
        $this->store = new Store(Database::getInstance());
        $this->history = new History(Database::getInstance());
        $this->alert = new Alert(Database::getInstance());
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
        $alert_type = $_POST['alert_type'] ?? 'absolute';
        $threshold_value = (float) ($_POST['threshold_value'] ?? 0);
        $notif_channels = $_POST['notify_channels'] ?? [];
        $check_interval = $_POST['check_interval'] ?? '60 minutes';
        $target_price = (float) ($_POST['target_price'] ?? 0);

        if (!$pending) {
            $this->redirect('/dashboard');
        }

        $store = $this->store->findOrCreate($pending['url']);
        $product = $this->product->findOrCreate($pending['parsed'], $store['store_id'], $user_id, $pending['url']);
        $this->history->create($product['product_id'], (float) $product['current_price']);
        $this->alert->create($user_id, $product['product_id'], $alert_type, $threshold_value, $notif_channels, $check_interval, $target_price);

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
