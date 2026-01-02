<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

$artists = fetchAll("SELECT * FROM artists WHERE active = 1 ORDER BY name");
trackPageView('artists');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artists - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <h1 class="page-title">Featured Artists</h1>
        <div class="grid grid-4">
            <?php foreach ($artists as $a): ?>
            <a href="artist.php?slug=<?= e($a['slug']) ?>" class="card card-center">
                <img src="<?= e($a['image'] ?: 'assets/img/placeholder.png') ?>" class="avatar-lg">
                <div class="card-body">
                    <h3><?= e($a['name']) ?></h3>
                    <span class="badge"><?= e($a['genre']) ?></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
