<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

$djs = fetchAll("SELECT * FROM djs WHERE active = 1 ORDER BY name");
trackPageView('djs');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DJs - <?= e(SITE_NAME) ?></title>
    <meta name="description" content="Meet the talented DJs of <?= e(SITE_NAME) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="page-content">
        <div class="container">
            <div class="page-header">
                <span class="section-badge">The Team</span>
                <h1 class="page-title">Meet Our DJs</h1>
                <p class="page-subtitle">The talented artists who bring you the best music every day</p>
            </div>
            
            <?php if (empty($djs)): ?>
            <p class="text-center text-muted">No DJs available at the moment.</p>
            <?php else: ?>
            <div class="grid grid-4">
                <?php foreach ($djs as $dj): ?>
                <div class="card dj-card">
                    <img src="<?= e($dj['image'] ?: 'assets/img/placeholder.png') ?>" alt="<?= e($dj['name']) ?>" class="avatar">
                    <h3><?= e($dj['name']) ?></h3>
                    <span class="role">DJ</span>
                    <?php if ($dj['bio']): ?>
                    <p style="color:var(--text-muted);font-size:0.9rem;margin-top:0.5rem"><?= e(substr($dj['bio'], 0, 80)) ?><?= strlen($dj['bio']) > 80 ? '...' : '' ?></p>
                    <?php endif; ?>
                    <?php $social = json_decode($dj['social_links'], true) ?: []; ?>
                    <?php if (!empty($social)): ?>
                    <div class="dj-social">
                        <?php if (!empty($social['instagram'])): ?><a href="<?= e($social['instagram']) ?>" target="_blank" title="Instagram">IG</a><?php endif; ?>
                        <?php if (!empty($social['twitter'])): ?><a href="<?= e($social['twitter']) ?>" target="_blank" title="Twitter">X</a><?php endif; ?>
                        <?php if (!empty($social['soundcloud'])): ?><a href="<?= e($social['soundcloud']) ?>" target="_blank" title="SoundCloud">SC</a><?php endif; ?>
                        <?php if (!empty($social['mixcloud'])): ?><a href="<?= e($social['mixcloud']) ?>" target="_blank" title="Mixcloud">MX</a><?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
