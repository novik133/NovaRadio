<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

$id = (int)($_GET['id'] ?? 0);
$podcast = fetch("SELECT * FROM podcasts WHERE id = ? AND active = 1", [$id]);
if (!$podcast) { header('Location: podcasts.php'); exit; }

$episodes = fetchAll("SELECT * FROM episodes WHERE podcast_id = ? AND active = 1 ORDER BY published_at DESC", [$id]);
trackPageView('podcast-' . $id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($podcast['title']) ?> - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <div class="podcast-header">
            <?php if ($podcast['image']): ?><img src="<?= e($podcast['image']) ?>" class="podcast-cover"><?php endif; ?>
            <div class="podcast-info">
                <span class="badge"><?= e($podcast['category']) ?></span>
                <h1><?= e($podcast['title']) ?></h1>
                <p class="podcast-author">By <?= e($podcast['author']) ?></p>
                <p><?= e($podcast['description']) ?></p>
            </div>
        </div>
        <h2>Episodes (<?= count($episodes) ?>)</h2>
        <div class="episodes-list">
            <?php foreach ($episodes as $ep): ?>
            <div class="episode-card">
                <div class="episode-info">
                    <h3><?= e($ep['title']) ?></h3>
                    <p><?= e(substr($ep['description'], 0, 150)) ?>...</p>
                    <div class="episode-meta">
                        <span><?= date('M j, Y', strtotime($ep['published_at'])) ?></span>
                        <span><?= floor($ep['duration'] / 60) ?>:<?= str_pad($ep['duration'] % 60, 2, '0', STR_PAD_LEFT) ?></span>
                        <span><?= $ep['downloads'] ?> downloads</span>
                    </div>
                </div>
                <audio controls preload="none"><source src="<?= e($ep['audio_url']) ?>" type="audio/mpeg"></audio>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
