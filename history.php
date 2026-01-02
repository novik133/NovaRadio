<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

$station = getDefaultStation();
$history = fetchAll("SELECT * FROM song_history WHERE station_id = ? ORDER BY played_at DESC LIMIT 50", [$station['id'] ?? 0]);
trackPageView('history');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Song History - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <h1 class="page-title">Recently Played</h1>
        <div class="history-full">
            <?php foreach ($history as $track): ?>
            <div class="history-item">
                <?php if ($track['artwork']): ?>
                <img src="<?= e($track['artwork']) ?>" class="history-artwork">
                <?php else: ?>
                <div class="history-artwork placeholder">🎵</div>
                <?php endif; ?>
                <div class="history-info">
                    <strong><?= e($track['title']) ?></strong>
                    <span><?= e($track['artist']) ?></span>
                    <?php if ($track['album']): ?><small><?= e($track['album']) ?></small><?php endif; ?>
                </div>
                <span class="history-time"><?= date('g:i A', strtotime($track['played_at'])) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
