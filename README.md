# Price Parser / Pricerr

[ENG](#eng) | [RUS](#rus)

## ENG

### Overview

`price_parser` is a custom PHP web application for tracking product prices on marketplaces.

The project lets a user:

- register and log in
- add a product by URL
- parse product data from a marketplace page
- save alert rules for price drops
- receive notifications via Telegram and email
- view current tracked products and price history

At the moment, the active parser in the application flow is `Wildberries`. Parsers for other marketplaces exist in the codebase, but the factory currently marks them as unsupported.

### Main Features

- Custom MVC-style PHP structure without a full framework
- PostgreSQL database
- Background price checks via cron script
- Alert rules by absolute drop, percent drop, or target price
- Notification channels: Telegram and email
- Product dashboard with edit/delete/statistics screens
- Region-aware parsing through a selected city stored per user

### Tech Stack

- PHP 8+
- PostgreSQL
- Composer
- `vlucas/phpdotenv`
- `phpmailer/phpmailer`
- Vanilla HTML/CSS/JS frontend

### Project Structure

```text
public/       Web entrypoint and static assets
src/
  Controllers/  HTTP controllers
  Core/         Router, session, validation
  Database/     PDO singleton wrapper
  Models/       Database models
  Services/     Parsing, alerts, notifications, auth
views/        PHP templates
cron/         Background price-check script
migrations/   SQL schema and migration helper script
```

### Supported Flow

1. User registers or logs in.
2. User selects a city/region.
3. User adds a product URL from a supported marketplace.
4. The app parses the product name, image, and current price.
5. User configures:
   - alert type
   - threshold value
   - target price
   - notification channels
   - check interval
6. Cron runs background checks and updates price history.
7. If an alert condition is met, the app sends a notification.

### Database Entities

- `users`
- `stores`
- `products`
- `price_history`
- `alerts`
- `notification_logs`

### Environment Variables

The code references these environment variables:

```env
DB_HOST=
DB_PORT=
DB_NAME=
DB_USER=
DB_PASS=

TG_BOT_TOKEN=

MAIL_HOST=
MAIL_PORT=
MAIL_AUTH=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ADDRESS=
MAIL_NAME=
MAIL_ENCRYPTION=
MAIL_SMTP_DEBUG=
```

### Installation

1. Install PHP, Composer, and PostgreSQL.
2. Make sure required PHP extensions are available, especially:
   - `pdo_pgsql`
   - `curl`
   - `mbstring`
   - `json`
3. Install dependencies:

```bash
composer install
```

4. Create and fill `.env`.
5. Create the PostgreSQL database.
6. Apply SQL files from `migrations/`.

You can use the helper script:

```bash
bash migrations/migrate.sh
```

Important: `migrations/migrate.sh` currently contains hardcoded database settings, so review and adjust it before running in another environment.

### Running Locally

The simplest local run option is PHP built-in server:

```bash
php -S localhost:8000 -t public
```

Then open:

```text
http://localhost:8000
```

### Cron Job

Background checks are executed by:

```bash
php cron/check_prices.php
```

Example cron entry:

```cron
*/30 * * * * /usr/bin/php /path/to/project/cron/check_prices.php >> /path/to/project/cron/cron.log 2>&1
```

The script:

- loads environment variables
- finds active products whose check interval is due
- parses the latest marketplace price
- stores price history
- updates current product price
- triggers notifications when alert conditions are met

### Telegram Integration

- The app exposes a webhook endpoint at `/telegram/webhook`
- User settings page generates a Telegram bot link with a `/start` payload
- After linking, alerts can be delivered to the saved Telegram chat ID

### Current Notes

- `Wildberries` is the only marketplace enabled by the parser factory right now
- Email notifications are implemented
- Telegram notifications are implemented
- SMS is present as a UI option/placeholder, but not fully wired in the active notification service
- Some settings UI actions appear unfinished
- No automated tests are included in the repository at the moment

### Useful Files

- `public/index.php` - app bootstrap and routes
- `cron/check_prices.php` - background monitoring entrypoint
- `src/Services/Parsers/ParserFactory.php` - marketplace selection logic
- `src/Services/PriceCheckService.php` - periodic price checking
- `src/Services/AlertService.php` - alert trigger logic

---

## RUS

### Обзор

`price_parser` - это веб-приложение на PHP для отслеживания цен товаров на маркетплейсах.

Проект позволяет:

- регистрироваться и входить в систему
- добавлять товар по ссылке
- парсить данные товара со страницы маркетплейса
- задавать правила уведомлений о снижении цены
- получать уведомления в Telegram и по email
- смотреть текущие товары в отслеживании и историю цен

Сейчас в основном пользовательском сценарии реально поддерживается `Wildberries`. В репозитории есть парсеры и для других площадок, но фабрика парсеров в текущем состоянии считает их неподдерживаемыми.

### Основные возможности

- Собственная MVC-подобная структура на PHP без тяжелого фреймворка
- База данных PostgreSQL
- Фоновая проверка цен через cron
- Правила уведомлений по абсолютному падению, проценту падения и целевой цене
- Каналы уведомлений: Telegram и email
- Дашборд с товарами, редактированием, удалением и статистикой
- Учет региона пользователя через выбранный город

### Технологии

- PHP 8+
- PostgreSQL
- Composer
- `vlucas/phpdotenv`
- `phpmailer/phpmailer`
- Обычный HTML/CSS/JS без frontend-фреймворка

### Структура проекта

```text
public/       Точка входа в веб-приложение и статические файлы
src/
  Controllers/  Контроллеры HTTP
  Core/         Роутер, сессии, валидация
  Database/     Обертка над PDO
  Models/       Модели для работы с БД
  Services/     Парсинг, уведомления, авторизация, бизнес-логика
views/        PHP-шаблоны
cron/         Скрипт фоновой проверки цен
migrations/   SQL-миграции и вспомогательный скрипт
```

### Как работает приложение

1. Пользователь регистрируется или входит в аккаунт.
2. Выбирает город/регион.
3. Добавляет ссылку на товар из поддерживаемого магазина.
4. Приложение парсит название, изображение и текущую цену.
5. Пользователь настраивает:
   - тип уведомления
   - порог
   - целевую цену
   - каналы уведомлений
   - интервал проверки
6. Cron запускает фоновые проверки и обновляет историю цен.
7. Если условие алерта выполнено, отправляется уведомление.

### Сущности базы данных

- `users`
- `stores`
- `products`
- `price_history`
- `alerts`
- `notification_logs`

### Переменные окружения

В коде используются такие переменные окружения:

```env
DB_HOST=
DB_PORT=
DB_NAME=
DB_USER=
DB_PASS=

TG_BOT_TOKEN=

MAIL_HOST=
MAIL_PORT=
MAIL_AUTH=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ADDRESS=
MAIL_NAME=
MAIL_ENCRYPTION=
MAIL_SMTP_DEBUG=
```

### Установка

1. Установите PHP, Composer и PostgreSQL.
2. Убедитесь, что доступны необходимые расширения PHP, особенно:
   - `pdo_pgsql`
   - `curl`
   - `mbstring`
   - `json`
3. Установите зависимости:

```bash
composer install
```

4. Создайте и заполните `.env`.
5. Создайте базу данных PostgreSQL.
6. Примените SQL-файлы из `migrations/`.

Можно использовать вспомогательный скрипт:

```bash
bash migrations/migrate.sh
```

Важно: в `migrations/migrate.sh` сейчас зашиты параметры подключения к базе, поэтому перед использованием в другом окружении скрипт лучше проверить и поправить.

### Локальный запуск

Самый простой способ запустить проект локально:

```bash
php -S localhost:8000 -t public
```

После этого откройте:

```text
http://localhost:8000
```

### Cron

Фоновая проверка цен запускается так:

```bash
php cron/check_prices.php
```

Пример записи в cron:

```cron
*/30 * * * * /usr/bin/php /path/to/project/cron/check_prices.php >> /path/to/project/cron/cron.log 2>&1
```

Скрипт:

- загружает переменные окружения
- выбирает активные товары, для которых пришло время проверки
- парсит актуальную цену
- сохраняет историю цен
- обновляет текущую цену товара
- отправляет уведомления при выполнении условий алерта

### Интеграция с Telegram

- В приложении есть webhook-эндпоинт `/telegram/webhook`
- На странице настроек формируется ссылка на Telegram-бота с payload для `/start`
- После привязки аккаунта уведомления могут приходить в сохраненный `chat_id`

### Текущее состояние

- Сейчас через фабрику реально включен только `Wildberries`
- Email-уведомления реализованы
- Telegram-уведомления реализованы
- `SMS` пока выглядит как заготовка в интерфейсе и не подключен полностью в рабочем notification flow
- Часть настроек интерфейса еще не доведена до конца
- Автоматических тестов в репозитории сейчас нет

### Полезные файлы

- `public/index.php` - bootstrap приложения и маршруты
- `cron/check_prices.php` - точка входа фонового мониторинга
- `src/Services/Parsers/ParserFactory.php` - выбор парсера маркетплейса
- `src/Services/PriceCheckService.php` - периодическая проверка цен
- `src/Services/AlertService.php` - логика срабатывания уведомлений
