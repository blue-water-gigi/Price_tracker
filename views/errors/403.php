<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 // ACCESS_DENIED</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>

<body>

    <div class="scanlines"></div>

    <header class="top-bar">
        <div class="logo">
            <span class="system-label">SYS_ERROR:</span>
            <span class="blink" style="color: var(--accent-danger); text-shadow: 0 0 10px var(--accent-danger);">403</span>
        </div>
        <div class="top-nav">
            <a href="/" class="nav-btn">&lt;&lt; BACK_TO_ROOT</a>
        </div>
    </header>

    <main class="console-wrapper">
        <div class="auth-container">
            <div class="terminal-window large">
                <div class="window-header">
                    <span class="dot-red"></span>
                    <span class="dot-yellow"></span>
                    <span class="dot-green"></span>
                    <span class="window-title">SECURITY_MODULE // ACCESS_VIOLATION_0x403</span>
                </div>

                <div style="padding: 2rem; display: flex; flex-direction: column; gap: 1.5rem;">

                    <div class="alert-bar error">
                        <span>[!]</span>
                        <span>CRITICAL: ACCESS_DENIED — недостаточно привилегий для выполнения операции</span>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div class="command-line">
                            <span class="prompt">></span>
                            <span style="font-family: var(--font-mono); font-size: 0.88rem; color: var(--text-muted); letter-spacing: 0.06em;">
                                RESOLVING: <span style="color: var(--accent-danger);"><?= convert($_SERVER['REQUEST_URI'] ?? '/restricted') ?></span>
                            </span>
                        </div>
                        <div class="command-line">
                            <span class="prompt">></span>
                            <span style="font-family: var(--font-mono); font-size: 0.88rem; color: var(--text-muted); letter-spacing: 0.06em;">
                                STATUS: <span style="color: var(--accent-danger);">PERMISSION_DENIED</span>
                            </span>
                        </div>
                        <div class="command-line">
                            <span class="prompt">></span>
                            <span style="font-family: var(--font-mono); font-size: 0.88rem; color: var(--text-muted); letter-spacing: 0.06em;">
                                SUGGESTION: <span style="color: var(--text-secondary);">войдите в систему или обратитесь к администратору</span>
                            </span>
                        </div>
                    </div>

                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        <a href="/login" class="btn-execute" style="flex: 2; text-align: center; padding: 13px;">
                            АВТОРИЗОВАТЬСЯ В СИСТЕМЕ
                        </a>
                        <a href="/" class="action-btn" style="flex: 1; padding: 13px; text-align: center;">
                            ГЛАВНАЯ
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <footer class="bottom-bar">
        <div class="status-indicator"><span class="dot" style="background: var(--accent-danger); box-shadow: 0 0 6px var(--accent-danger);"></span> ERROR_STATE: 403</div>
        <div class="timestamp">ACCESS_LOG_UPDATED // 2026</div>
    </footer>

</body>

</html>