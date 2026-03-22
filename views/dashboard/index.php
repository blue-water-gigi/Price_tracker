<!DOCTYPE html>
<html lang="ru">

<?php include __DIR__ . "/../partials/head.php" ?>

<body class="dashboard-page">

    <?php include __DIR__ . "/../partials/header.php" ?>

    <main class="console-wrapper">

        <?php
        $errors = App\Core\Session::getFlash('error');
        $succes = App\Core\Session::getFlash('success');
        $old = App\Core\Session::getFlash('old') ?? [];
        ?>

        <?php if ($errors) { ?>
            <div>
                <?php if (is_array($errors)) { ?>
                    <?php foreach ($errors as $field => $fieldErrors) { ?>
                        <?php foreach ($fieldErrors as $error) { ?>
                            <div class="error-msg">[!] <?= convert($error) ?></div>
                        <?php } ?>
                    <?php } ?>
                <?php } else { ?>
                    <div class="error-msg">[!] <?= convert($errors) ?></div>
                <?php } ?>
            </div>
        <?php } ?>

        <?php if ($succes) { ?>
            <div><?= convert($succes) ?></div>
        <?php } ?>

        <section class="cmd-section">
            <div class="terminal-window">
                <div class="window-header">
                    <span class="dot-green"></span>
                    <span class="window-title">ADD_NEW_TARGET.BAT</span>
                </div>
                <form action="/dashboard/add" method="POST" class="cmd-form">
                    <span class="prompt">ID@ROOT:~$</span>
                    <input value="<?= convert($old['url'] ?? '') ?>" type="url" name="url" placeholder="ENTER_PRODUCT_URL..." required>
                    <button type="submit" class="btn-execute mini">TRACK</button>
                </form>
            </div>
        </section>

        <div class="grid-label">[ ACTIVE_MONITORING_NODES ]</div>

        <section class="products-grid">

            <?php if (empty($products)) { ?>
                <div class="product-card">// NO_ACTIVE_MONITORING_NODES</div>
            <?php } else { ?>
                <?php foreach ($products as $product) { ?>
                    <div class="product-card">
                        <div class="card-edge"></div>
                        <div class="card-content">
                            <div class="product-header">
                                <span class="product-id">
                                    <?= $product['product_id'] ?>
                                </span>
                                <span class="product-source">
                                    <?= convert($product['store_name']) ?>
                                </span>
                            </div>

                            <div class="product-img">
                                <img src="<?= convert($product['image_url']) ?>" alt="product">
                            </div>

                            <h3 class="product-title"><?= convert($product['name']) ?></h3>

                            <div class="price-info">
                                <div class="price-current">
                                    <?php $curr = convert($product['currency']) ?>
                                    <?= number_format($product['current_price'], 0, '.', ' ')  . " " . $curr ?>
                                </div>
                                <!-- todo make visibale status changes <div class="price-status down"></div> -->
                            </div>

                            <div class="link">
                                <a target="_blank" href="<?= convert($product['url']) ?>">Ссылка на товар</a>
                            </div>

                            <div class="card-actions">
                                <a href="/product/<?= $product['product_id'] ?>" class="action-btn">HISTORY</a>
                                <a href="/product/<?= $product['product_id'] ?>/delete" class="action-btn danger">DELETE</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </section>
    </main>

    <?php include __DIR__ . "/../partials/footer.php" ?>

    <?php include __DIR__ . "/../partials/scripts.php" ?>

</body>

</html>