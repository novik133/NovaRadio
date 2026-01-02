<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

$slug = $_GET['slug'] ?? '';
$page = fetch("SELECT * FROM pages WHERE slug = ? AND active = 1", [$slug]);
if (!$page) { header('HTTP/1.0 404 Not Found'); die('Page not found'); }
trackPageView('page-' . $slug);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page['title']) ?> - <?= e(SITE_NAME) ?></title>
    <meta name="description" content="<?= e($page['meta_description']) ?>">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <main class="page-content">
        <div class="container container-sm">
            <h1 class="page-title"><?= e($page['title']) ?></h1>
            <div class="content-block"><?= $page['content'] ?></div>
        </div>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
