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
                <a href="/dashboard/settings" class="menu-item">SETTINGS.EXE</a>
                <a href="/logs" class="menu-item">SYSTEM_LOGS</a>
                <div class="menu-divider"></div>
                <a href="/logout" class="menu-item logout">TERMINATE_SESSION</a>
            </div>
        </div>
    </div>
</header>