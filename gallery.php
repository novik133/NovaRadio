<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

$slug = $_GET['slug'] ?? '';
$gallery = fetch("SELECT * FROM galleries WHERE slug = ? AND active = 1", [$slug]);
if (!$gallery) { header('Location: galleries.php'); exit; }

$images = fetchAll("SELECT * FROM gallery_images WHERE gallery_id = ? ORDER BY sort_order", [$gallery['id']]);
trackPageView('gallery-' . $gallery['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($gallery['title']) ?> - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <h1 class="page-title"><?= e($gallery['title']) ?></h1>
        <?php if ($gallery['description']): ?><p class="text-center"><?= e($gallery['description']) ?></p><?php endif; ?>
        <div class="gallery-grid">
            <?php foreach ($images as $img): ?>
            <div class="gallery-item" onclick="openLightbox('<?= e($img['image']) ?>', '<?= e($img['caption']) ?>')">
                <img src="<?= e($img['image']) ?>" alt="<?= e($img['caption']) ?>" loading="lazy">
                <?php if ($img['caption']): ?><div class="gallery-caption"><?= e($img['caption']) ?></div><?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div id="lightbox" class="lightbox" onclick="closeLightbox()">
        <span class="lightbox-close">&times;</span>
        <img id="lightbox-img" src="">
        <div id="lightbox-caption"></div>
    </div>
    <script>
    function openLightbox(src, caption) { document.getElementById('lightbox').classList.add('active'); document.getElementById('lightbox-img').src = src; document.getElementById('lightbox-caption').textContent = caption; }
    function closeLightbox() { document.getElementById('lightbox').classList.remove('active'); }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });
    </script>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
