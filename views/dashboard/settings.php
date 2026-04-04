<!DOCTYPE html>
<html lang="ru">

<?php include __DIR__ . "/../partials/head.php" ?>

<body class="dashboard-page">

    <div class="scanlines"></div>

    <?php include __DIR__ . "/../partials/header.php" ?>

    <main class="console-wrapper">

        <div class="top-nav" style="margin-bottom:0.5rem;">
            <a href="/dashboard" class="nav-btn">
                &lt;&lt; BACK</a>
        </div>

        <div class="grid-label">[ USER_CONFIGURATION // SESSION: <?= strtoupper(App\Core\Session::get('username')) ?> ]
        </div>

        <div class="settings-layout">

            <nav class="settings-nav">
                <div class="settings-nav-header">// CONFIG_MODULES</div>

                <div class="settings-nav-item active" data-panel="profile">
                    <span class="settings-nav-icon">▸</span> PROFILE
                </div>
                <div class="settings-nav-item" data-panel="security">
                    <span class="settings-nav-icon">▸</span> SECURITY
                </div>
                <div class="settings-nav-item" data-panel="notifications">
                    <span class="settings-nav-icon">▸</span> NOTIFICATIONS
                </div>
            </nav>

            <div class="settings-panels">

                <div class="settings-panel active" id="panel-profile">

                    <div class="settings-section">
                        <div class="settings-section-header">
                            <span class="settings-section-title">// IDENTITY_NODE</span>
                        </div>
                        <div class="settings-section-body">
                            <div class="settings-avatar-row">
                                <div class="settings-avatar">
                                    <img src="https://api.dicebear.com/7.x/pixel-art/svg?seed=<?= App\Core\Session::get('username') ?>"
                                        alt="avatar">
                                </div>
                                <div class="settings-avatar-info">
                                    <div class="settings-avatar-name" id="displayName">
                                        <?= convert(strtoupper(App\Core\Session::get('username'))) ?>
                                    </div>
                                    <div class="settings-avatar-meta">ROOT_PRIVILEGES // ACTIVE_SESSION</div>
                                    <div class="settings-avatar-meta" id="displayEmail"
                                        style="color: var(--text-secondary);">
                                        <?= convert(App\Core\Session::get('email') ?? '') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="settings-section">
                        <div class="settings-section-header">
                            <span class="settings-section-title">// CHANGE_USERNAME</span>
                        </div>
                        <form class="settings-section-body" method="POST" action="/dashboard/settings">
                            <div class="settings-field">
                                <span class="settings-field-label">ТЕКУЩИЙ:</span>
                                <span class="settings-field-value" id="currentUsername">
                                    <?= convert(App\Core\Session::get('username')) ?>
                                </span>
                            </div>
                            <div class="settings-field">
                                <label class="settings-field-label" for="newUsername">НОВЫЙ:</label>
                                <div class="input-wrapper">
                                    <span class="prompt">></span>
                                    <input type="text" id="newUsername" name="username"
                                        placeholder="введите новый username" autocomplete="off">
                                </div>
                            </div>
                        
                        <div class="settings-save-row">
                            <span class="save-status" id="status-username"></span>
                            <button type="submit" class="btn-execute" style="width:auto; padding: 10px 28px;">
                                ПРИМЕНИТЬ</button>
                        </div>

                        </form>
                    </div>

                    <?php
                        $cities = [
                            'Москва и область'                        => -1257786,
                            'Санкт-Петербург и Ленинградская область' => -1257787,
                            'Казань'                                  => -1029256,
                            'Екатеринбург'                            => -1113276,
                            'Краснодар'                               => -1181034,
                            'Новосибирск'                             => -1216601,
                            'Хабаровск'                               => -1221148,
                            'Минск'                                   => -1075841,
                            'Алматы'                                  => -2133462,
                        ];
                        $currentCity = convert($user['city'] ?? '—');
                    ?>
                    <div class="settings-section">
                        <div class="settings-section-header">
                            <span class="settings-section-title">// CHANGE_CITY</span>
                        </div>
                        <div class="settings-section-body">

                            <div class="settings-field">
                                <span class="settings-field-label">ТЕКУЩИЙ:</span>
                                <span class="settings-field-value" id="currentCity"><?= $currentCity ?></span>
                            </div>

                            <form id="citySettingsForm"
                                  action="/dashboard/city"
                                  method="POST">
                                <div class="settings-field" style="margin-bottom:1rem;">
                                    <label class="settings-field-label" for="newCity">НОВЫЙ:</label>
                                    <select name="city"
                                            id="newCity"
                                            class="terminal-select">
                                        <option value="" disabled selected>— выберите регион —</option>
                                        <?php foreach ($cities as $name => $id): ?>
                                            <option value="<?= htmlspecialchars($name) ?>"
                                                <?= ($user['city'] ?? '') === $name ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($name) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="settings-save-row">
                                    <button type="submit" class="btn-execute"
                                            style="width:auto; padding: 10px 28px;">ПРИМЕНИТЬ</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="settings-panel" id="panel-security">

                    <div class="settings-section">
                        <div class="settings-section-header">
                            <span class="settings-section-title">// CHANGE_ACCESS_KEY</span>
                        </div>
                        <div class="settings-section-body">

                            <div class="settings-field">
                                <label class="settings-field-label" for="currentPassword">ТЕКУЩИЙ ПАРОЛЬ:</label>
                                <div class="input-wrapper">
                                    <span class="prompt">></span>
                                    <input type="password" id="currentPassword" name="current_password"
                                        placeholder="••••••••">
                                </div>
                            </div>

                            <div class="settings-field">
                                <label class="settings-field-label" for="newPassword">НОВЫЙ ПАРОЛЬ:</label>
                                <div style="display:flex; flex-direction:column; gap:6px; flex:1;">
                                    <div class="input-wrapper">
                                        <span class="prompt">></span>
                                        <input type="password" id="newPassword" name="new_password"
                                            placeholder="••••••••" oninput="checkStrength(this.value)">
                                    </div>
                                    <div class="password-strength">
                                        <div class="strength-track">
                                            <div class="strength-fill" id="strengthFill"></div>
                                        </div>
                                        <span class="strength-label" id="strengthLabel">STRENGTH: —</span>
                                    </div>
                                </div>
                            </div>

                            <div class="settings-field">
                                <label class="settings-field-label" for="confirmPassword">ПОДТВЕРЖДЕНИЕ:</label>
                                <div class="input-wrapper" id="confirmWrapper">
                                    <span class="prompt">></span>
                                    <input type="password" id="confirmPassword" name="confirm_password"
                                        placeholder="••••••••" oninput="checkConfirm()">
                                </div>
                            </div>

                        </div>
                        <div class="settings-save-row">
                            <span class="save-status" id="status-password"></span>
                            <button class="btn-execute" style="width:auto; padding: 10px 28px;"
                                onclick="savePassword()">ОБНОВИТЬ ПАРОЛЬ</button>
                        </div>
                    </div>

                </div>

                <div class="settings-panel" id="panel-notifications">

                    <?php
                    $userId = App\Core\Session::get('user_id') ?? '';
                    $botUsername = 'Price_pricerr_bot';
                    $payload = base64_encode($userId);
                    $tgLink = "https://t.me/{$botUsername}?start={$payload}";
                    $tgConnected = $tg_chat_id['telegram_chat_id'] !== null;
                    ?>

                    <div class="settings-section">
                        <div class="settings-section-header">
                            <span class="settings-section-title">// TELEGRAM_BINDING</span>
                        </div>
                        <div class="settings-section-body">

                            <div class="tg-connect-block">
                                <div class="tg-status-indicator">
                                    <span class="tg-dot <?= $tgConnected ? 'connected' : 'disconnected' ?>"
                                        id="tgDot"></span>
                                    <span id="tgStatusText"
                                        class="tg-status-text <?= $tgConnected ? 'connected' : 'disconnected' ?>"
                                        data-connected="<?= $tgConnected ? '1' : '0' ?>">
                                        <?= $tgConnected ? 'ПРИВЯЗАН' : 'НЕ ПРИВЯЗАН' ?>
                                    </span>
                                </div>

                                <?php if (!$tgConnected): ?>
                                    <a target="_blank" href="<?= convert($tgLink) ?>" class="btn-execute"
                                        style="width:auto; padding: 10px 24px;" id="tgConnectBtn">
                                        ПРИВЯЗАТЬ TELEGRAM
                                    </a>
                                <?php else: ?>
                                    <span class="settings-field-value"
                                        style="color: var(--accent-dim); font-size: 0.78rem;">
                                        ID: <?= convert((string) $tg_chat_id['telegram_chat_id']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <?php if (!$tgConnected): ?>
                                <div class="tg-code-hint" style="margin-top: 0.75rem;">
                                    &gt; Нажмите кнопку — откроется <span
                                        style="color:var(--accent);">@<?= $botUsername ?></span><br>
                                    &gt; Отправьте боту команду <span style="color:var(--accent);">/start</span> —
                                    привязка произойдёт автоматически
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>

                </div><!-- /panel-notifications -->

            </div><!-- /settings-panels -->
        </div><!-- /settings-layout -->

    </main>

    <?php include __DIR__ . "/../partials/footer.php" ?>
    <?php include __DIR__ . "/../partials/scripts.php" ?>

</body>

</html>