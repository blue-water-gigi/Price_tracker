<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 // NODE_NOT_FOUND</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>

<body>

    <div class="scanlines"></div>

    <header class="top-bar">
        <div class="logo">
            <span class="system-label">SYS_ERROR:</span>
            <span class="blink">404</span>
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
                    <span class="window-title">KERNEL_PANIC // EXCEPTION_0x404</span>
                </div>

                <div style="padding: 2rem; display: flex; flex-direction: column; gap: 1.5rem;">

                    <div class="alert-bar error">
                        <span>[!]</span>
                        <span>FATAL: NODE_NOT_FOUND — запрошенный ресурс не существует в системе</span>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div class="command-line">
                            <span class="prompt">></span>
                            <span style="font-family: var(--font-mono); font-size: 0.88rem; color: var(--text-muted); letter-spacing: 0.06em;">
                                RESOLVING: <span style="color: var(--accent-danger);"><?= convert($_SERVER['REQUEST_URI'] ?? '/unknown') ?></span>
                            </span>
                        </div>
                        <div class="command-line">
                            <span class="prompt">></span>
                            <span style="font-family: var(--font-mono); font-size: 0.88rem; color: var(--text-muted); letter-spacing: 0.06em;">
                                STATUS: <span style="color: var(--accent-danger);">OBJECT_DOES_NOT_EXIST</span>
                            </span>
                        </div>
                        <div class="command-line">
                            <span class="prompt">></span>
                            <span style="font-family: var(--font-mono); font-size: 0.88rem; color: var(--text-muted); letter-spacing: 0.06em;">
                                SUGGESTION: <span style="color: var(--text-secondary);">проверьте правильность URL или вернитесь на главную</span>
                            </span>
                        </div>
                    </div>

                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        <a href="/" class="btn-execute" style="flex: 2; text-align: center; padding: 13px;">
                            ВЕРНУТЬСЯ НА ГЛАВНУЮ
                        </a>
                        <a href="/dashboard" class="action-btn" style="flex: 1; padding: 13px; text-align: center;">
                            DASHBOARD
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <footer class="bottom-bar">
        <div class="status-indicator"><span class="dot" style="background: var(--accent-danger); box-shadow: 0 0 6px var(--accent-danger);"></span> ERROR_STATE: 404</div>
        <div class="timestamp">EXCEPTION_LOGGED // 2026</div>
    </footer>

</body>

</html>