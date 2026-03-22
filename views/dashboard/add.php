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
                            <span class="product-id">ID: NEW_ENTRY</span>
                            <span class="product-source">Wildberries</span>
                        </div>
                        <div class="product-img">
                            <img src="<?= $pending['parsed']['image_url'] ?>" alt="preview">
                            <div class="scanner-line"></div>
                        </div>
                        <h3 class="product-title"><?= $pending['parsed']['name'] ?></h3>
                        <div class="price-info">
                            <div class="price-current"><?= number_format($pending['parsed']['price'], 0, '.', ' ') ?> ₽</div>
                            <div class="price-status awaiting">AWAITING_DATA...</div>
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

                <form action="/dashboard/save" method="POST" class="config-form">

                    <div class="form-grid">

                        <!-- Threshold type + value -->
                        <div class="threshold-block">
                            <label class="section-label">ТИП УВЕДОМЛЕНИЯ О СНИЖЕНИИ:</label>
                            <div name="alert_type" class="type-toggle">
                                <input type="button" value="₽ АБСОЛЮТНОЕ" name="absolute" class="type-toggle-btn" data-type="absolute">
                                <input type="button" value="% ОТНОСИТЕЛЬНОЕ" name="percent" class="type-toggle-btn" data-type="percent">
                            </div>

                            <div class="threshold-input-wrap" id="thresholdInputWrap">
                                <label>ПОРОГОВОЕ ЗНАЧЕНИЕ:</label>
                                <div class="input-wrapper">
                                    <span class="prompt">></span>
                                    <input
                                        type="number"
                                        id="thresholdValue"
                                        name="threshold_value"
                                        placeholder="..."
                                        min="0"
                                        step="any"
                                        required>
                                    <span class="input-suffix" id="thresholdSuffix"></span>
                                </div>
                            </div>

                            <input type="hidden" name="alert_type" id="thresholdType" value="absolute">
                        </div>

                        <div class="input-group">
                            <label>ИНТЕРВАЛ ПРОВЕРКИ:</label>
                            <select name="check_interval" class="terminal-select">
                                <option value="15 minutes">15 МИНУТ</option>
                                <option value="60 minutes" selected>1 ЧАС</option>
                                <option value="360 minutes">6 ЧАСОВ</option>
                                <option value="1440 minutes">24 ЧАСА</option>
                            </select>
                        </div>

                    </div>

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