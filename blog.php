<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

$category = $_GET['category'] ?? '';
$page = max(1, (int)($_GET['p'] ?? 1));
$perPage = 9;
$offset = ($page - 1) * $perPage;

$where = "WHERE active = 1 AND (published_at IS NULL OR published_at <= NOW())";
$params = [];
if ($category) { $where .= " AND category = ?"; $params[] = $category; }

$total = fetch("SELECT COUNT(*) as c FROM posts $where", $params)['c'];
$posts = fetchAll("SELECT p.*, a.username as author FROM posts p LEFT JOIN admins a ON p.author_id = a.id $where ORDER BY featured DESC, published_at DESC, created_at DESC LIMIT $perPage OFFSET $offset", $params);
$categories = fetchAll("SELECT DISTINCT category FROM posts WHERE active = 1 AND category IS NOT NULL AND category != ''");
$featured = !$category && $page === 1 ? fetch("SELECT * FROM posts WHERE featured = 1 AND active = 1 ORDER BY published_at DESC LIMIT 1") : null;

trackPageView('blog');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog<?= $category ? ' - ' . e($category) : '' ?> - <?= e(SITE_NAME) ?></title>
    <meta name="description" content="Latest news, updates, and articles from <?= e(SITE_NAME) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="page-content">
        <div class="container">
            <div class="page-header">
                <span class="section-badge">News & Updates</span>
                <h1 class="page-title"><?= $category ? e($category) : 'Blog' ?></h1>
                <p class="page-subtitle">Stay updated with the latest news, interviews, and music reviews</p>
            </div>
            
            <?php if ($featured): ?>
            <a href="post.php?slug=<?= e($featured['slug']) ?>" class="featured-post" style="margin-bottom:3rem">
                <?php if ($featured['image']): ?>
                <img src="<?= e($featured['image']) ?>" alt="<?= e($featured['title']) ?>">
                <?php else: ?>
                <div style="width:100%;height:100%;background:linear-gradient(135deg,var(--primary),var(--accent))"></div>
                <?php endif; ?>
                <div class="featured-post-content">
                    <span class="badge" style="background:var(--accent);color:white">Featured</span>
                    <h2><a href="post.php?slug=<?= e($featured['slug']) ?>"><?= e($featured['title']) ?></a></h2>
                    <p><?= e($featured['excerpt'] ?: substr(strip_tags($featured['content']), 0, 150)) ?>...</p>
                </div>
            </a>
            <?php endif; ?>

            <?php if (!empty($categories)): ?>
            <div style="display:flex;gap:0.5rem;flex-wrap:wrap;margin-bottom:2rem;justify-content:center">
                <a href="blog.php" class="btn <?= !$category ? 'btn-primary' : 'btn-outline' ?>" style="padding:0.5rem 1rem;font-size:0.9rem">All Posts</a>
                <?php foreach ($categories as $cat): ?>
                <a href="blog.php?category=<?= urlencode($cat['category']) ?>" class="btn <?= $category === $cat['category'] ? 'btn-primary' : 'btn-outline' ?>" style="padding:0.5rem 1rem;font-size:0.9rem"><?= e($cat['category']) ?></a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if (empty($posts)): ?>
            <div class="text-center" style="padding:4rem 0">
                <p class="text-muted">No posts found<?= $category ? ' in this category' : '' ?>.</p>
            </div>
            <?php else: ?>
            <div class="grid grid-3">
                <?php foreach ($posts as $post): ?>
                <article class="card post-card">
                    <a href="post.php?slug=<?= e($post['slug']) ?>">
                        <?php if ($post['image']): ?>
                        <img src="<?= e($post['image']) ?>" alt="<?= e($post['title']) ?>" class="card-img">
                        <?php else: ?>
                        <div class="card-img" style="background:linear-gradient(135deg,var(--bg-elevated),var(--bg-card));display:flex;align-items:center;justify-content:center;font-size:3rem">📰</div>
                        <?php endif; ?>
                    </a>
                    <div class="card-body">
                        <div class="post-meta">
                            <?php if ($post['category']): ?><span class="badge"><?= e($post['category']) ?></span><?php endif; ?>
                            <span><?= date('M j, Y', strtotime($post['published_at'] ?? $post['created_at'])) ?></span>
                        </div>
                        <h3><a href="post.php?slug=<?= e($post['slug']) ?>"><?= e($post['title']) ?></a></h3>
                        <p><?= e(substr($post['excerpt'] ?: strip_tags($post['content']), 0, 100)) ?>...</p>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php $totalPages = ceil($total / $perPage); if ($totalPages > 1): ?>
            <div style="display:flex;justify-content:center;gap:0.5rem;margin-top:3rem">
                <?php if ($page > 1): ?>
                <a href="?p=<?= $page - 1 ?><?= $category ? '&category=' . urlencode($category) : '' ?>" class="btn btn-outline">← Previous</a>
                <?php endif; ?>
                
                <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                <a href="?p=<?= $i ?><?= $category ? '&category=' . urlencode($category) : '' ?>" class="btn <?= $i === $page ? 'btn-primary' : 'btn-outline' ?>" style="min-width:44px"><?= $i ?></a>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                <a href="?p=<?= $page + 1 ?><?= $category ? '&category=' . urlencode($category) : '' ?>" class="btn btn-outline">Next →</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </main>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>
