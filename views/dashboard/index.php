<!DOCTYPE html>
<html lang="ru">

<?php include __DIR__ . "/../partials/head.php" ?>

<body class="dashboard-page">

    <?php include __DIR__ . "/../partials/header.php" ?>

    <main class="console-wrapper">

        <?php
        $errors = App\Core\Session::getFlash('error');
        $success = App\Core\Session::getFlash('success');
        $old = App\Core\Session::getFlash('old') ?? [];
        $counter = 1;
        ?>

        <section class="cmd-section">
            <div class="terminal-window">
                <div class="window-header">
                    <span class="dot-green"></span>
                    <span class="window-title">ADD_NEW_TARGET.BAT</span>
                </div>
                <form action="/dashboard/add" method="POST" class="cmd-form">
                    <span class="prompt">ID@ROOT:~$</span>
                    <input value="<?= convert($old['url'] ?? '') ?>" type="url" name="url"
                        placeholder="ENTER_PRODUCT_URL..." required>
                    <button type="submit" class="btn-execute mini">TRACK</button>
                </form>
            </div>
        </section>

        <?php if ($errors): ?>
            <div class="error-box" style="margin-bottom:1rem;">
                <?php if (is_array($errors)): ?>
                    <?php foreach ($errors as $fieldErrors): ?>
                        <?php foreach ((array) $fieldErrors as $error): ?>
                            <div class="error-msg">[!] <?= convert($error) ?></div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="error-msg">[!] <?= convert($errors) ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert-bar success" style="margin-bottom:1rem;">
                <span>[✓]</span><span><?= convert($success) ?></span>
            </div>
        <?php endif; ?>

        <div class="dashboard-toolbar">
            <div class="group-filters" id="groupFilters">
                <button class="group-filter-btn active" data-group="all">
                    ALL <span class="group-count" id="countAll"></span>
                </button>
                <!-- группы рендерятся через js -->
            </div>
            <button class="group-create-btn" id="openGroupModal">+ NEW_GROUP</button>
        </div>

        <div class="grid-label" id="gridLabel">[ ACTIVE_MONITORING_NODES ]</div>

        <section class="products-grid" id="productsGrid">

            <?php if (empty($products)): ?>
                <div class="empty-node">
                    // NO_ACTIVE_MONITORING_NODES<br>
                    <span style="color:var(--text-dim);">Добавьте первый товар через строку выше</span>
                </div>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <?php
                    $price = (float) $product['current_price'];
                    $prevPrice = (float) ($product['previous_price'] ?? $price);
                    $delta = $price - $prevPrice;
                    $deltaPct = $prevPrice > 0 ? round($delta / $prevPrice * 100, 1) : 0;
                    $deltaClass = $delta < 0 ? 'down' : ($delta > 0 ? 'up' : 'flat');
                    $deltaSign = $delta > 0 ? '+' : '';
                    $currency = convert($product['currency'] ?? '₽');
                    $priceStr = $price == 0
                        ? 'НЕТ В НАЛИЧИИ'
                        : number_format($price, 0, '.', ' ') . ' ' . $currency;
                    ?>
                    <div class="product-card" data-id="<?= $product['product_id'] ?>" data-group="">
                        <div class="card-edge"></div>
                        <div class="card-content">

                            <!-- Header -->
                            <div class="product-header">
                                <span class="product-id">#<?= $counter++ ?></span>
                                <span class="product-source"><?= convert($product['store_name']) ?></span>
                            </div>

                            <!-- Image -->
                            <div class="product-img">
                                <img src="<?= convert($product['image_url']) ?>" alt="product">
                                <div class="scanner-line"></div>
                            </div>

                            <!-- Title -->
                            <h3 class="product-title"><?= convert($product['name']) ?></h3>

                            <!-- Price + delta -->
                            <div class="price-info">
                                <div class="price-current <?= $price == 0 ? 'price-unavailable' : '' ?>">
                                    <?= $priceStr ?>
                                </div>
                            </div>

                            <?php if ($price > 0 && $delta != 0): ?>
                                <div class="price-delta <?= $deltaClass ?>">
                                    <?= $deltaSign . number_format($delta, 0, '.', ' ') . ' ' . $currency ?>
                                    <span style="opacity:0.7">(<?= $deltaSign . $deltaPct ?>%)</span>
                                </div>
                            <?php elseif ($price > 0): ?>
                                <div class="price-delta flat">— NO_CHANGE</div>
                            <?php endif; ?>

                            <!-- Link -->
                            <a class="product-link" href="<?= convert($product['url']) ?>" target="_blank">
                                ↗ OPEN_SOURCE
                            </a>

                            <!-- Group select -->
                            <select class="card-group-select" data-id="<?= $product['product_id'] ?>"
                                onchange="assignGroup(this)">
                                <option value="">— GROUP: NONE —</option>
                            </select>

                            <!-- Actions -->
                            <div class="card-actions">
                                <a href="/product/<?= $product['product_id'] ?>/edit" class="action-btn"
                                    style="flex:1; text-align:center;">EDIT</a>

                                <a href="/product/<?= $product['product_id'] ?>/stats" class="action-btn"
                                    style="flex:1; text-align:center;">STATISTICS</a>

                                <form action="/product/<?= $product['product_id'] ?>" method="POST" style="flex:1; margin:0;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="action-btn danger" style="width:100%;">DELETE</button>
                                </form>

                            </div>

                        </div>

                        <!-- Group badge -->
                        <div class="card-group-badge" id="badge-<?= $product['product_id'] ?>"></div>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </section>
    </main>

    <!-- Modal: создание группы -->
    <div class="modal-overlay" id="groupModal">
        <div class="modal-box">
            <div class="window-header">
                <span class="dot-red"></span>
                <span class="dot-yellow"></span>
                <span class="dot-green"></span>
                <span class="window-title">CREATE_GROUP.EXE</span>
            </div>
            <div class="modal-body">
                <div class="modal-title">// ENTER_GROUP_NAME</div>
                <div class="input-wrapper">
                    <span class="prompt">></span>
                    <input type="text" id="groupNameInput" placeholder="напр. НОУТБУКИ" autocomplete="off"
                        maxlength="24" style="text-transform:uppercase;">
                </div>
                <div class="modal-actions">
                    <button class="btn-execute" onclick="createGroup()">СОЗДАТЬ</button>
                    <button class="modal-cancel-btn" onclick="closeGroupModal()">ОТМЕНА</button>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . "/../partials/footer.php" ?>

    <?php include __DIR__ . "/../partials/scripts.php" ?>

</body>

</html>