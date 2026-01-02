<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

$slug = $_GET['slug'] ?? '';
$artist = fetch("SELECT * FROM artists WHERE slug = ? AND active = 1", [$slug]);
if (!$artist) { header('Location: artists.php'); exit; }

$social = json_decode($artist['social_links'], true) ?: [];
trackPageView('artist-' . $artist['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($artist['name']) ?> - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <div class="artist-profile">
            <img src="<?= e($artist['image'] ?: 'assets/img/placeholder.png') ?>" class="artist-image">
            <div class="artist-info">
                <span class="badge"><?= e($artist['genre']) ?></span>
                <h1><?= e($artist['name']) ?></h1>
                <p><?= nl2br(e($artist['bio'])) ?></p>
                <?php if ($artist['website']): ?>
                <a href="<?= e($artist['website']) ?>" target="_blank" class="btn btn-outline">Visit Website</a>
                <?php endif; ?>
                <?php if ($social): ?>
                <div class="artist-social">
                    <?php foreach ($social as $platform => $url): if ($url): ?>
                    <a href="<?= e($url) ?>" target="_blank"><?= ucfirst($platform) ?></a>
                    <?php endif; endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
