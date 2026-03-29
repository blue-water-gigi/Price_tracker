<?php $error = App\Core\Session::getFlash('error') ?? '' ?>

<!DOCTYPE html>
<html lang="ru">

<?php include __DIR__ . "/../partials/head.php" ?>

<body class="dashboard-page">

    <?php include __DIR__ . "/../partials/header.php" ?>

    <main class="console-wrapper">
        <div class="grid-label">[ CONFIGURATION_INTERFACE // TARGET_ID: 0x442 ]</div>

        <section class="config-layout">
            <aside class="config-preview">
                <div class="product-card preview-mode">
                    <div class="card-edge"></div>
                    <div class="card-content">
                        <div class="product-header">
                            <span class="product-id">ID: EDIT_PRODUCT</span>
                            <span class="product-source">Wildberries</span>
                        </div>
                        <div class="product-img">
                            <img src="<?= $product['image_url'] ?>" alt="preview">
                            <div class="scanner-line"></div>
                        </div>
                        <h3 class="product-title"><?= $product['name'] ?></h3>
                        <div class="price-info">
                            <div class="price-current"><?= number_format($product['current_price'], 0, '.', ' ') === '0' ? 'Нет в наличии' : number_format($product['current_price'], 0, '.', ' ') ?> ₽</div>
                            <div class="price-status awaiting">EDITING...</div>
                        </div>
                    </div>
                </div>
                <p class="helper-text">// ПРЕДВАРИТЕЛЬНЫЙ ПРОСМОТР ОБЪЕКТА</p>
            </aside>

            <div class="terminal-window">
                <div class="window-header">
                    <span class="dot-green"></span>
                    <span class="window-title">SET_LIMITS_AND_NOTIFICATIONS.EXE</span>
                </div>

                <form action="/product/<?= $product['product_id'] ?>" method="POST" class="config-form"
                    data-product-price="<?= (int)$product['current_price'] ?>">
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="form-grid">

                        <!-- ── Блок 1: Тип + пороговое значение ────────────── -->
                        <div class="threshold-block">
                            <label class="section-label">ТИП УВЕДОМЛЕНИЯ О СНИЖЕНИИ:</label>

                            <div class="type-toggle">
                                <input type="button" value="₽ АБСОЛЮТНОЕ" class="type-toggle-btn active" data-type="absolute">
                                <input type="button" value="% ОТНОСИТЕЛЬНОЕ" class="type-toggle-btn" data-type="percent">
                            </div>

                            <input type="hidden" name="alert_type" id="thresholdType" value="absolute">

                            <!-- Слайдер порогового значения -->
                            <div class="threshold-input-wrap visible" id="thresholdInputWrap">
                                <div class="slider-block">
                                    <div class="slider-header">
                                        <span class="slider-label">ПОРОГОВОЕ ЗНАЧЕНИЕ:</span>
                                        <span class="slider-value-badge" id="thresholdBadge">0 ₽</span>
                                    </div>

                                    <input
                                        type="range"
                                        id="thresholdRange"
                                        class="terminal-range"
                                        min="0"
                                        max="<?= (int)$product['current_price'] ?>"
                                        value="0"
                                        step="1"
                                        style="--val: 0%">

                                    <div class="slider-ticks">
                                        <span>0</span>
                                        <span id="thresholdMax"><?= number_format((int)$product['current_price'], 0, '.', ' ') ?> ₽</span>
                                    </div>

                                    <div class="slider-manual-row">
                                        <span class="slider-manual-label">ВВОД:</span>
                                        <div class="input-wrapper" style="flex:1">
                                            <span class="prompt">></span>
                                            <input
                                                type="number"
                                                id="thresholdValue"
                                                name="threshold_value"
                                                placeholder="0"
                                                min="0"
                                                step="any"
                                                required>
                                            <span class="input-suffix" id="thresholdSuffix">₽</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ── Блок 2: Интервал проверки ───────────────────── -->
                        <div class="input-group">
                            <label>ИНТЕРВАЛ ПРОВЕРКИ:</label>
                            <select name="check_interval" class="terminal-select">
                                <option value="30 minutes">30 МИНУТ</option>
                                <option value="60 minutes" selected>1 ЧАС</option>
                                <option value="360 minutes">6 ЧАСОВ</option>
                                <option value="1440 minutes">24 ЧАСА</option>
                            </select>
                        </div>

                    </div>

                    <div class="form-divider"></div>

                    <!-- ── Целевая цена ─────────────────────────────────────── -->
                    <div class="target-price-block">
                        <label class="section-label">ЦЕЛЕВАЯ ЦЕНА (КУПИТЬ КОГДА ≤ X):</label>

                        <div class="slider-block">
                            <div class="slider-header">
                                <span class="slider-label">ЖЕЛАЕМАЯ ЦЕНА:</span>
                                <span class="slider-value-badge" id="targetBadge">0 ₽</span>
                            </div>

                            <input
                                type="range"
                                id="targetRange"
                                class="terminal-range"
                                min="0"
                                max="<?= (int)$product['current_price'] ?>"
                                value="0"
                                step="1"
                                style="--val: 0%">

                            <div class="slider-ticks">
                                <span>0</span>
                                <span><?= number_format((int)$product['current_price'], 0, '.', ' ') ?> ₽</span>
                            </div>

                            <div class="slider-manual-row">
                                <span class="slider-manual-label">ВВОД:</span>
                                <div class="input-wrapper" style="flex:1">
                                    <span class="prompt">></span>
                                    <input
                                        type="number"
                                        id="targetValue"
                                        name="target_price"
                                        placeholder="0"
                                        min="0"
                                        step="any">
                                    <span class="input-suffix">₽</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-divider"></div>

                    <!-- ── Каналы уведомлений ──────────────────────────────── -->
                    <div class="notification-settings">
                        <label class="section-label">КАНАЛЫ УВЕДОМЛЕНИЙ:</label>
                        <div class="checkbox-list">
                            <label class="custom-checkbox">
                                <input type="checkbox" name="notify_channels[]" value="telegram" checked>
                                <span class="checkmark"></span>
                                TELEGRAM_BOT
                            </label>
                            <label class="custom-checkbox">
                                <input type="checkbox" name="notify_channels[]" value="email">
                                <span class="checkmark"></span>
                                EMAIL_SYSTEM
                            </label>
                            <label class="custom-checkbox">
                                <input type="checkbox" name="notify_channels[]" value="sms">
                                <span class="checkmark"></span>
                                PHONE_PUSH
                            </label>
                        </div>
                    </div>

                    <div class="form-actions-row">
                        <button type="submit" class="btn-execute">ПОДТВЕРДИТЬ</button>
                        <a href="/dashboard/cancel" class="action-btn cancel-btn">ОТМЕНИТЬ</a>
                    </div>

                </form>
            </div>
        </section>
    </main>

    <?php include __DIR__ . "/../partials/footer.php" ?>
    <?php include __DIR__ . "/../partials/scripts.php" ?>

</body>

</html>