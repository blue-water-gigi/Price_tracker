<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SYS_AUTH // NEW_USER</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

    <div class="scanlines"></div>

    <header class="top-bar">
        <div class="logo">
            <span class="system-label">AUTH_MODULE:</span>
            <span class="blink">REGISTRATION</span>
        </div>
        <div class="top-nav">
            <a href="/" class="nav-btn">
                << BACK</a>
        </div>
    </header>

    <main class="console-wrapper">
        <section class="auth-container">
            <div class="terminal-window large">
                <div class="window-header">
                    <span class="dot-red"></span>
                    <span class="dot-yellow"></span>
                    <span class="dot-green"></span>
                    <span class="window-title">CREATE_NEW_ENTITY.EXE</span>
                </div>

                <?php $old = App\Core\Session::getFlash('old') ?? [] ?>

                <form action="/register" method="POST" class="terminal-form">
                    <div class="input-group">
                        <label>IDENTITY (EMAIL):</label>
                        <div class="input-wrapper">
                            <span class="prompt">></span>
                            <input value="<?= convert($old['email'] ?? '') ?>" type="email" name="email" required placeholder="...">
                        </div>
                    </div>

                    <div class="input-group">
                        <label>USERNAME:</label>
                        <div class="input-wrapper">
                            <span class="prompt">></span>
                            <input value="<?= convert($old['username'] ?? '') ?>" type="text" name="username" required placeholder="Admin">
                        </div>
                    </div>

                    <div class="input-group">
                        <label>ACCESS_KEY (PASSWORD):</label>
                        <div class="input-wrapper">
                            <span class="prompt">></span>
                            <input type="password" name="password" required placeholder="...">
                        </div>
                    </div>

                    <div class="input-group">
                        <label>CONFIRM_KEY:</label>
                        <div class="input-wrapper">
                            <span class="prompt">></span>
                            <input type="password" name="password_confirm" required placeholder="...">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-execute">ВЫПОЛНИТЬ ИНИЦИАЛИЗАЦИЮ</button>
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
                        <?php  } ?>
                    </div>
                <?php } ?>

            </div>
        </section>
    </main>

    <footer class="bottom-bar">
        <div class="status-indicator"><span class="dot"></span> SECURE_PROVISIONING</div>
    </footer>

</body>

</html>