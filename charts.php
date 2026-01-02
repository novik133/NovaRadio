<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

$station = getDefaultStation();
$chart = fetchAll("SELECT * FROM charts WHERE station_id = ? AND period = 'weekly' ORDER BY chart_date DESC, position LIMIT 20", [$station['id'] ?? 0]);
trackPageView('charts');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Charts - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <h1 class="page-title">Weekly Top 20</h1>
        <div class="chart-list">
            <?php foreach ($chart as $track): ?>
            <div class="chart-item">
                <span class="chart-position"><?= $track['position'] ?></span>
                <div class="chart-movement">
                    <?php if ($track['previous_position'] && $track['previous_position'] > $track['position']): ?>
                        <span class="up">▲</span>
                    <?php elseif ($track['previous_position'] && $track['previous_position'] < $track['position']): ?>
                        <span class="down">▼</span>
                    <?php else: ?>
                        <span class="same">●</span>
                    <?php endif; ?>
                </div>
                <div class="chart-info">
                    <strong><?= e($track['title']) ?></strong>
                    <span><?= e($track['artist']) ?></span>
                </div>
                <span class="chart-weeks"><?= $track['weeks_on_chart'] ?> week<?= $track['weeks_on_chart'] > 1 ? 's' : '' ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
