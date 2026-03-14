<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SYS_DASH // TERMINAL</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/dashboard.css">
</head>

<body class="dashboard-page">

    <div class="scanlines"></div>

    <header class="top-bar">
        <div class="logo">
            <span class="system-label">SYS_CORE:</span>
            <span class="blink">OPERATOR_DASHBOARD</span>
        </div>

        <div class="header-right">
            <div class="user-profile" id="profileTrigger">
                <div class="avatar-wrapper">
                    <div class="status-online"></div>
                    <img src="https://api.dicebear.com/7.x/pixel-art/svg?seed=Operator" alt="AV">
                </div>
                <div class="user-info">
                    <span class="u-name"><?= $_SESSION['username'] ?></span>
                    <span class="u-role">ROOT_PRIVILEGES</span>
                </div>
                <div class="dropdown-menu" id="profileMenu">
                    <div class="menu-header">// SESSION_CONTROLS</div>
                    <a href="/settings" class="menu-item">SETTINGS.EXE</a>
                    <a href="/logs" class="menu-item">SYSTEM_LOGS</a>
                    <div class="menu-divider"></div>
                    <a href="/logout" class="menu-item logout">TERMINATE_SESSION</a>
                </div>
            </div>
        </div>
    </header>

    <main class="console-wrapper">
        <section class="cmd-section">
            <div class="terminal-window">
                <div class="window-header">
                    <span class="dot-green"></span>
                    <span class="window-title">ADD_NEW_TARGET.BAT</span>
                </div>
                <form action="/add-product" method="POST" class="cmd-form">
                    <span class="prompt">ID@ROOT:~$</span>
                    <input type="url" name="url" placeholder="ENTER_PRODUCT_URL..." required>
                    <button type="submit" class="btn-execute mini">TRACK</button>
                </form>
            </div>
        </section>

        <div class="grid-label">[ ACTIVE_MONITORING_NODES ]</div>

        <section class="products-grid">
            <?php for ($i = 0; $i < 4; $i++): ?>
                <div class="product-card">
                    <div class="card-edge"></div>
                    <div class="card-content">
                        <div class="product-header">
                            <span class="product-id">NODE_0<?php echo $i + 1; ?></span>
                            <span class="product-source">OZON.RU</span>
                        </div>

                        <div class="product-img">
                            <img src="https://via.placeholder.com/150/000000/00ff41?text=IMAGE_DATA" alt="product">
                        </div>

                        <h3 class="product-title">NVIDIA RTX 4090 TI / 24GB</h3>

                        <div class="price-info">
                            <div class="price-current">185,990₽</div>
                            <div class="price-status down">▼ 4.2%</div>
                        </div>

                        <div class="card-actions">
                            <button class="action-btn">HISTORY</button>
                            <button class="action-btn danger">DELETE</button>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </section>
    </main>

    <footer class="bottom-bar">
        <div class="status-indicator"><span class="dot"></span> NODE_STATUS: OPTIMAL</div>
        <div class="timestamp">SECURE_LINK_ACTIVE // 2026</div>
    </footer>

    <script src="assets/dashboard.js"></script>
</body>

</html>