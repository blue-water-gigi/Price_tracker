<?php
$historyRows = is_array($history ?? null) ? $history : [];
$historySorted = $historyRows;
usort($historySorted, static fn(array $a, array $b): int => strcmp((string) ($a['checked_at'] ?? ''), (string) ($b['checked_at'] ?? '')));

$pointsCount = count($historySorted);
$currentPrice = (float) ($product['current_price'] ?? 0);
$name = (string) ($product['name'] ?? 'UNKNOWN_PRODUCT');
$url = (string) ($product['url'] ?? '#');
$image = (string) ($product['image_url'] ?? '');
$currency = 'RUB';

$prices = array_map(static fn(array $row): float => (float) ($row['price'] ?? 0), $historySorted);
$firstPrice = $pointsCount > 0 ? (float) $prices[0] : $currentPrice;
$minPrice = $pointsCount > 0 ? min($prices) : $currentPrice;
$maxPrice = $pointsCount > 0 ? max($prices) : $currentPrice;
$avgPrice = $pointsCount > 0 ? array_sum($prices) / $pointsCount : $currentPrice;

$changeAbs = $currentPrice - $firstPrice;
$changePct = $firstPrice > 0 ? ($changeAbs / $firstPrice) * 100 : 0;
$changeSign = $changeAbs > 0 ? '+' : '';
$changeClass = $changeAbs < 0 ? 'down' : ($changeAbs > 0 ? 'up' : 'awaiting');

$firstDate = $historySorted[0]['checked_at'] ?? null;
$lastDate = $historySorted[$pointsCount - 1]['checked_at'] ?? null;

$chartLabels = array_map(
    static fn(array $row): string => date('d.m H:i', strtotime((string) ($row['checked_at'] ?? 'now'))),
    $historySorted
);
$chartPrices = array_map(static fn(array $row): float => (float) ($row['price'] ?? 0), $historySorted);
$chartPayload = [
    'labels' => $chartLabels,
    'prices' => $chartPrices,
];
?>

<!DOCTYPE html>
<html lang="ru">

<?php include __DIR__ . "/../partials/head.php" ?>

<body class="dashboard-page">

    <?php include __DIR__ . "/../partials/header.php" ?>

    <main class="console-wrapper">
        <div class="grid-label">[ PRODUCT_STATISTICS_MODULE // TARGET_ID: <?= (int) ($product['product_id'] ?? 0) ?> ]
        </div>

        <div class="top-nav" style="margin-bottom:0.5rem;">
            <a href="/dashboard" class="nav-btn">
                << BACK</a>
        </div>

        <section class="config-layout" style="margin-bottom:1.5rem;">
            <aside class="config-preview">
                <div class="product-card preview-mode">
                    <div class="card-edge"></div>
                    <div class="card-content">
                        <div class="product-header">
                            <span class="product-id">ID: <?= (int) ($product['product_id'] ?? 0) ?></span>
                            <span class="product-source">TRACKED_ITEM</span>
                        </div>
                        <div class="product-img">
                            <img src="<?= convert($image) ?>" alt="product">
                            <div class="scanner-line"></div>
                        </div>
                        <h3 class="product-title"><?= convert($name) ?></h3>
                        <div class="price-info">
                            <div class="price-current"><?= number_format($currentPrice, 0, '.', ' ') ?> ₽</div>
                        </div>
                        <div class="price-status <?= $changeClass ?>">
                            CHANGE_FROM_START: <?= $changeSign . number_format($changeAbs, 0, '.', ' ') ?> ₽
                            (<?= $changeSign . number_format($changePct, 1, '.', ' ') ?>%)
                        </div>
                        <a class="product-link" href="<?= convert($url) ?>" target="_blank">OPEN_PRODUCT_PAGE ↗</a>
                    </div>
                </div>
                <p class="helper-text">// ОСНОВНОЙ ОБЪЕКТ МОНИТОРИНГА И ТЕКУЩИЙ СТАТУС</p>
            </aside>

            <div class="terminal-window" style="max-width:none;">
                <div class="window-header">
                    <span class="dot-red"></span>
                    <span class="dot-yellow"></span>
                    <span class="dot-green"></span>
                    <span class="window-title">PRICE_STATS_OVERVIEW.EXE</span>
                </div>

                <div style="padding:1.25rem;">
                    <section class="stats-grid" style="margin-top:0;">
                        <article class="stat-card">
                            <div class="card-label">Текущая цена</div>
                            <div class="card-value"><?= number_format($currentPrice, 0, '.', ' ') ?> ₽</div>
                        </article>
                        <article class="stat-card">
                            <div class="card-label">Минимум за период</div>
                            <div class="card-value"><?= number_format($minPrice, 0, '.', ' ') ?> ₽</div>
                        </article>
                        <article class="stat-card">
                            <div class="card-label">Максимум за период</div>
                            <div class="card-value"><?= number_format($maxPrice, 0, '.', ' ') ?> ₽</div>
                        </article>
                        <article class="stat-card">
                            <div class="card-label">Средняя цена</div>
                            <div class="card-value"><?= number_format($avgPrice, 0, '.', ' ') ?> ₽</div>
                        </article>
                    </section>

                    <section class="stats-grid" style="margin-top:1rem;">
                        <article class="stat-card">
                            <div class="card-label">Количество точек</div>
                            <div class="card-value"><?= $pointsCount ?></div>
                        </article>
                        <article class="stat-card">
                            <div class="card-label">Валюта</div>
                            <div class="card-value"><?= convert($currency) ?></div>
                        </article>
                        <article class="stat-card">
                            <div class="card-label">Первый check</div>
                            <div class="card-value">
                                <?= $firstDate ? date('d.m.Y H:i', strtotime((string) $firstDate)) : '—' ?>
                            </div>
                        </article>
                        <article class="stat-card">
                            <div class="card-label">Последний check</div>
                            <div class="card-value">
                                <?= $lastDate ? date('d.m.Y H:i', strtotime((string) $lastDate)) : '—' ?>
                            </div>
                        </article>
                    </section>
                </div>
            </div>
        </section>

        <section class="products-grid" style="grid-template-columns:1fr; gap:1rem;">
            <article class="terminal-window" style="max-width:none;">
                <div class="window-header">
                    <span class="dot-green"></span>
                    <span class="window-title">CHART_SLOT_01 // PRICE_TREND</span>
                </div>
                <div style="min-height:320px; padding:1rem; color:var(--text-muted); font-size:0.85rem;">
                    <canvas id="priceTrendChart" height="320"></canvas>
                </div>
            </article>

            <article class="terminal-window" style="max-width:none;">
                <div class="window-header">
                    <span class="dot-green"></span>
                    <span class="window-title">CHART_SLOT_02 // DISTRIBUTION</span>
                </div>
                <div style="min-height:260px; padding:1rem; color:var(--text-muted); font-size:0.85rem;">
                    <canvas id="priceDistributionChart" height="260"></canvas>
                </div>
            </article>

            <article class="terminal-window" style="max-width:none;">
                <div class="window-header">
                    <span class="dot-green"></span>
                    <span class="window-title">RECENT_HISTORY_LOG.TXT</span>
                </div>

                <div style="padding:1rem; overflow-x:auto;">
                    <?php if ($pointsCount === 0): ?>
                        <div class="empty-node" style="grid-column:auto;">
                            // HISTORY_DATA_NOT_FOUND<br>
                            <span style="color:var(--text-dim);">Нет данных для отображения статистики</span>
                        </div>
                    <?php else: ?>
                        <table style="width:100%; border-collapse:collapse; font-size:0.8rem;">
                            <thead>
                                <tr style="border-bottom:1px solid var(--border); color:var(--text-muted);">
                                    <th style="text-align:left; padding:0.55rem;">#</th>
                                    <th style="text-align:left; padding:0.55rem;">DATE_TIME</th>
                                    <th style="text-align:left; padding:0.55rem;">PRICE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_reverse($historySorted) as $idx => $row): ?>
                                    <tr style="border-bottom:1px solid rgba(0,255,65,0.08);">
                                        <td style="padding:0.55rem; color:var(--text-muted);"><?= $idx + 1 ?></td>
                                        <td style="padding:0.55rem;">
                                            <?= date('d.m.Y H:i', strtotime((string) $row['checked_at'])) ?>
                                        </td>
                                        <td style="padding:0.55rem; color:var(--accent);">
                                            <?= number_format((float) $row['price'], 0, '.', ' ') ?> ₽
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </article>
        </section>
    </main>

    <?php include __DIR__ . "/../partials/footer.php" ?>
    <?php include __DIR__ . "/../partials/scripts.php" ?>
    <script>
        window.statsChartData = <?= json_encode($chartPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script type="module" src="/assets/stats-charts.js"></script>

</body>

</html>