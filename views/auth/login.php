<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SYS_AUTH // ACCESS</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>

<body class="dashboard-page">
    <div class="scanlines"></div>

    <div class="scanlines"></div>

    <header class="top-bar">
        <div class="logo">
            <span class="system-label">AUTH_MODULE:</span>
            <span class="blink">LOGIN_REQUIRED</span>
        </div>
        <div class="top-nav">
            <a href="/" class="nav-btn">
                << BACK</a>
        </div>
    </header>

    <main class="console-wrapper">
        <section class="auth-container">
            <div class="terminal-window">
                <div class="window-header">
                    <span class="dot-red"></span>
                    <span class="dot-yellow"></span>
                    <span class="dot-green"></span>
                    <span class="window-title">USER_LOGIN.EXE</span>
                </div>

                <?php $old = App\Core\Session::getFlash('old'); ?>

                <form action="/login" method="POST" class="terminal-form">
                    <?= csrf() ?>

                    <div class="input-group">
                        <label>EMAIL:</label>
                        <div class="input-wrapper">
                            <span class="prompt">></span>
                            <input value="<?= convert($old['email'] ?? '') ?>" type="email" name="email" required
                                placeholder="...">
                        </div>
                    </div>

                    <div class="input-group">
                        <label>PASSWORD:</label>
                        <div class="input-wrapper">
                            <span class="prompt">></span>
                            <input type="password" name="password" required placeholder="...">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-execute">ВОЙТИ В СИСТЕМУ</button>
                        <p class="form-footer">Нет доступа? <a href="/register" class="link">Создать аккаунт</a></p>
                    </div>
                </form>

                <?php $errors = App\Core\Session::getFlash('errors'); ?>
                <?php if ($errors) { ?>
                    <div class="error-box">
                        <?php foreach ($errors as $field => $msg) { ?>
                            <p class="error-msg">
                                <span class="error-prefix">[!] CRITICAL_ERROR:</span>
                                <?= convert($msg[0]) ?>
                            </p>
                        <?php } ?>
                    </div>
                <?php } ?>

            </div>
        </section>
    </main>

    <footer class="bottom-bar">
        <div class="status-indicator"><span class="dot"></span> AWAITING_CREDENTIALS</div>
    </footer>

</body>

</html>