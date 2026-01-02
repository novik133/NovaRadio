<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

$podcasts = fetchAll("SELECT p.*, (SELECT COUNT(*) FROM episodes WHERE podcast_id = p.id AND active = 1) as episode_count FROM podcasts p WHERE p.active = 1 ORDER BY p.created_at DESC");
trackPageView('podcasts');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Podcasts - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <h1 class="page-title">Podcasts</h1>
        <div class="grid grid-3">
            <?php foreach ($podcasts as $pod): ?>
            <a href="podcast.php?id=<?= $pod['id'] ?>" class="card">
                <?php if ($pod['image']): ?><img src="<?= e($pod['image']) ?>" class="card-img"><?php endif; ?>
                <div class="card-body">
                    <span class="badge"><?= e($pod['category']) ?></span>
                    <h3><?= e($pod['title']) ?></h3>
                    <p><?= e(substr($pod['description'], 0, 100)) ?>...</p>
                    <span class="text-muted"><?= $pod['episode_count'] ?> episodes</span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
