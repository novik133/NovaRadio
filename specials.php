<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

$broadcasts = fetchAll("SELECT sb.*, s.name as station_name, d.name as dj_name FROM special_broadcasts sb LEFT JOIN stations s ON sb.station_id = s.id LEFT JOIN djs d ON sb.dj_id = d.id WHERE sb.end_time > NOW() ORDER BY sb.start_time");
trackPageView('specials');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Special Broadcasts - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <h1 class="page-title">Special Broadcasts</h1>
        <?php if (empty($broadcasts)): ?>
        <p class="text-center text-muted">No upcoming special broadcasts scheduled.</p>
        <?php else: ?>
        <div class="broadcasts-list">
            <?php foreach ($broadcasts as $b): ?>
            <div class="broadcast-card <?= $b['is_live'] ? 'is-live' : '' ?>">
                <?php if ($b['image']): ?><img src="<?= e($b['image']) ?>" class="broadcast-img"><?php endif; ?>
                <div class="broadcast-info">
                    <?php if ($b['is_live']): ?><span class="live-badge pulse">LIVE NOW</span><?php endif; ?>
                    <h3><?= e($b['title']) ?></h3>
                    <p><?= e($b['description']) ?></p>
                    <div class="broadcast-meta">
                        <span>📅 <?= date('M j, Y', strtotime($b['start_time'])) ?></span>
                        <span>🕐 <?= date('g:i A', strtotime($b['start_time'])) ?> - <?= date('g:i A', strtotime($b['end_time'])) ?></span>
                        <?php if ($b['dj_name']): ?><span>🎧 <?= e($b['dj_name']) ?></span><?php endif; ?>
                        <?php if ($b['station_name']): ?><span>📻 <?= e($b['station_name']) ?></span><?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
