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
                            <div class="price-current">
                                <?= number_format($product['current_price'], 0, '.', ' ') === '0'
                                    ? 'Нет в наличии'
                                    : number_format($product['current_price'], 0, '.', ' ') ?>
                                ₽
                            </div>
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

                <?php
                $alertType = $product['alert_type'] ?? 'absolute';
                $thresholdValue = (float) ($product['threshold_value'] ?? 0);
                $targetPrice = (float) ($product['target_price'] ?? 0);
                $currentPrice = (int) $product['current_price'];
                // max слайдера: для процентного типа — 100, для абсолютного — цена товара
                $threshMax = $alertType === 'percent' ? 100 : $currentPrice;
                $threshPct = $threshMax > 0
                    ? round(($thresholdValue / $threshMax) * 100, 2)
                    : 0;
                $targetPct = $currentPrice > 0
                    ? round(($targetPrice / $currentPrice) * 100, 2)
                    : 0;
                ?>

                <form action="/product/<?= $product['product_id'] ?>" method="POST" class="config-form"
                    data-product-price="<?= $currentPrice ?>">
                    <input type="hidden" name="_method" value="PATCH">
                    <?= csrf() ?>

                    <div class="form-grid">

                        <!-- ── Тип + пороговое значение ──────────────────── -->
                        <div class="threshold-block">
                            <label class="section-label">ТИП УВЕДОМЛЕНИЯ О СНИЖЕНИИ:</label>

                            <div class="type-toggle">
                                <input type="button" value="₽ АБСОЛЮТНОЕ"
                                    class="type-toggle-btn <?= $alertType === 'absolute' ? 'active' : '' ?>"
                                    data-type="absolute">
                                <input type="button" value="% ОТНОСИТЕЛЬНОЕ"
                                    class="type-toggle-btn <?= $alertType === 'percent' ? 'active' : '' ?>"
                                    data-type="percent">
                            </div>

                            <!-- hidden: тип читается JS при инициализации -->
                            <input type="hidden" name="alert_type" id="thresholdType"
                                value="<?= htmlspecialchars($alertType) ?>">

                            <div class="threshold-input-wrap visible" id="thresholdInputWrap">
                                <div class="slider-block">
                                    <div class="slider-header">
                                        <span class="slider-label">ПОРОГОВОЕ ЗНАЧЕНИЕ:</span>
                                        <span class="slider-value-badge" id="thresholdBadge"></span>
                                    </div>

                                    <input type="range" id="thresholdRange" class="terminal-range" min="0"
                                        max="<?= $threshMax ?>" value="<?= $thresholdValue ?>"
                                        step="<?= $alertType === 'percent' ? '0.1' : '1' ?>"
                                        style="--val: <?= $threshPct ?>%">

                                    <div class="slider-ticks">
                                        <span>0</span>
                                        <span id="thresholdMax">
                                            <?= $alertType === 'percent'
                                                ? '100 %'
                                                : number_format($currentPrice, 0, '.', ' ') . ' ₽' ?>
                                        </span>
                                    </div>

                                    <div class="slider-manual-row">
                                        <span class="slider-manual-label">ВВОД:</span>
                                        <div class="input-wrapper" style="flex:1">
                                            <span class="prompt">></span>
                                            <input type="number" id="thresholdValue" name="threshold_value"
                                                placeholder="0" min="0" step="any" required
                                                value="<?= $thresholdValue > 0 ? $thresholdValue : '' ?>">
                                            <span class="input-suffix" id="thresholdSuffix">
                                                <?= $alertType === 'percent' ? '%' : '₽' ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="threshold-abs-preview" id="thresholdAbsPreview">
                                        ≈ <span class="abs-value" id="thresholdAbsValue">0 ₽</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ── Интервал проверки ──────────────────────────── -->
                        <div class="input-group">
                            <label>ИНТЕРВАЛ ПРОВЕРКИ:</label>
                            <select name="check_interval" class="terminal-select">
                                <option value="30 minutes" <?= $product['check_interval'] === '00:30:00' ? 'selected' : '' ?>>30 МИНУТ</option>
                                <option value="60 minutes" <?= $product['check_interval'] === '01:00:00' ? 'selected' : '' ?>>1 ЧАС</option>
                                <option value="360 minutes" <?= $product['check_interval'] === '06:00:00' ? 'selected' : '' ?>>6 ЧАСОВ</option>
                                <option value="1440 minutes" <?= $product['check_interval'] === '24:00:00' ? 'selected' : '' ?>>24 ЧАСА</option>
                            </select>
                        </div>

                    </div>

                    <div class="form-divider"></div>

                    <!-- ── Целевая цена ───────────────────────────────────── -->
                    <div class="target-price-block">
                        <label class="section-label">ЦЕЛЕВАЯ ЦЕНА (КУПИТЬ КОГДА ≤ X):</label>

                        <div class="slider-block">
                            <div class="slider-header">
                                <span class="slider-label">ЖЕЛАЕМАЯ ЦЕНА:</span>
                                <span class="slider-value-badge" id="targetBadge"></span>
                            </div>

                            <input type="range" id="targetRange" class="terminal-range" min="0"
                                max="<?= $currentPrice ?>" value="<?= $targetPrice ?>" step="1"
                                style="--val: <?= $targetPct ?>%">

                            <div class="slider-ticks">
                                <span>0</span>
                                <span><?= number_format($currentPrice, 0, '.', ' ') ?> ₽</span>
                            </div>

                            <div class="slider-manual-row">
                                <span class="slider-manual-label">ВВОД:</span>
                                <div class="input-wrapper" style="flex:1">
                                    <span class="prompt">></span>
                                    <input type="number" id="targetValue" name="target_price" placeholder="0" min="0"
                                        step="any" value="<?= $targetPrice > 0 ? $targetPrice : '' ?>">
                                    <span class="input-suffix">₽</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-divider"></div>

                    <!-- ── Каналы уведомлений ─────────────────────────────── -->
                    <div class="notification-settings">
                        <label class="section-label">КАНАЛЫ УВЕДОМЛЕНИЙ:</label>
                        <div class="checkbox-list">
                            <label class="custom-checkbox">
                                <input type="checkbox" name="notify_channels[]" value="telegram"
                                    <?= str_contains($product['notification_channels'] ?? '', 'telegram') ? 'checked' : '' ?>>
                                <span class="checkmark"></span>
                                TELEGRAM_BOT
                            </label>
                            <label class="custom-checkbox">
                                <input type="checkbox" name="notify_channels[]" value="email"
                                    <?= str_contains($product['notification_channels'] ?? '', 'email') ? 'checked' : '' ?>>
                                <span class="checkmark"></span>
                                EMAIL_SYSTEM
                            </label>
                            <label class="custom-checkbox">
                                <input type="checkbox" name="notify_channels[]" value="sms"
                                    <?= str_contains($product['notification_channels'] ?? '', 'sms') ? 'checked' : '' ?>>
                                <span class="checkmark"></span>
                                PHONE_PUSH
                            </label>
                        </div>
                    </div>

                    <div class="form-actions-row">
                        <button type="submit" class="btn-execute">ПОДТВЕРДИТЬ</button>
                        <a href="/dashboard" class="action-btn cancel-btn">ОТМЕНИТЬ</a>
                    </div>

                </form>
            </div>
        </section>
    </main>

    <?php include __DIR__ . "/../partials/footer.php" ?>
    <?php include __DIR__ . "/../partials/scripts.php" ?>

</body>

</html>