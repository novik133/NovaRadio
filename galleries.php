<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

$galleries = fetchAll("SELECT g.*, (SELECT COUNT(*) FROM gallery_images WHERE gallery_id = g.id) as image_count FROM galleries g WHERE g.active = 1 ORDER BY g.created_at DESC");
trackPageView('gallery');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <h1 class="page-title">Gallery</h1>
        <div class="grid grid-3">
            <?php foreach ($galleries as $g): ?>
            <a href="gallery.php?slug=<?= e($g['slug']) ?>" class="card gallery-card">
                <?php if ($g['cover_image']): ?><img src="<?= e($g['cover_image']) ?>" class="card-img"><?php endif; ?>
                <div class="card-body">
                    <h3><?= e($g['title']) ?></h3>
                    <span class="text-muted"><?= $g['image_count'] ?> photos</span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
