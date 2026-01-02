<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

$slug = $_GET['slug'] ?? '';
$post = fetch("SELECT p.*, a.username as author FROM posts p LEFT JOIN admins a ON p.author_id = a.id WHERE p.slug = ? AND p.active = 1", [$slug]);
if (!$post) { header('Location: blog.php'); exit; }

query("UPDATE posts SET views = views + 1 WHERE id = ?", [$post['id']]);
trackPageView('post-' . $post['id']);

$comments = fetchAll("SELECT * FROM comments WHERE post_id = ? AND approved = 1 AND parent_id IS NULL ORDER BY created_at DESC", [$post['id']]);
$commentsEnabled = getSetting('comments_enabled', '1') === '1';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $commentsEnabled) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $parentId = $_POST['parent_id'] ?? null;
    if ($name && $email && $content) {
        $approved = getSetting('comments_moderation', '1') === '1' ? 0 : 1;
        insert('comments', ['post_id' => $post['id'], 'parent_id' => $parentId ?: null, 'author_name' => $name, 'author_email' => $email, 'content' => $content, 'approved' => $approved]);
        $msg = $approved ? 'Comment posted!' : 'Comment submitted for moderation.';
    }
}

$related = fetchAll("SELECT * FROM posts WHERE id != ? AND category = ? AND active = 1 ORDER BY published_at DESC LIMIT 3", [$post['id'], $post['category']]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($post['title']) ?> - <?= e(SITE_NAME) ?></title>
    <meta name="description" content="<?= e($post['excerpt']) ?>">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <article class="container post-single">
        <?php if ($post['image']): ?><img src="<?= e($post['image']) ?>" alt="<?= e($post['title']) ?>" class="post-hero"><?php endif; ?>
        <header class="post-header">
            <span class="badge"><?= e($post['category']) ?></span>
            <h1><?= e($post['title']) ?></h1>
            <div class="post-meta">
                <span>By <?= e($post['author'] ?? 'Admin') ?></span>
                <span><?= date('M j, Y', strtotime($post['published_at'])) ?></span>
                <span><?= $post['views'] ?> views</span>
            </div>
        </header>
        <div class="post-content"><?= $post['content'] ?></div>
        <?php if ($post['tags']): ?>
        <div class="post-tags"><?php foreach (explode(',', $post['tags']) as $tag): ?><span class="tag"><?= e(trim($tag)) ?></span><?php endforeach; ?></div>
        <?php endif; ?>

        <?php if ($commentsEnabled): ?>
        <section class="comments-section">
            <h3>Comments (<?= count($comments) ?>)</h3>
            <?php if (isset($msg)): ?><div class="alert"><?= e($msg) ?></div><?php endif; ?>
            <form method="post" class="comment-form">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <textarea name="content" placeholder="Your Comment" required></textarea>
                <button type="submit" class="btn btn-primary">Post Comment</button>
            </form>
            <div class="comments-list">
                <?php foreach ($comments as $c): ?>
                <div class="comment">
                    <strong><?= e($c['author_name']) ?></strong>
                    <span class="comment-date"><?= date('M j, Y', strtotime($c['created_at'])) ?></span>
                    <p><?= nl2br(e($c['content'])) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php if ($related): ?>
        <section class="related-posts">
            <h3>Related Posts</h3>
            <div class="grid grid-3">
                <?php foreach ($related as $r): ?>
                <a href="post.php?slug=<?= e($r['slug']) ?>" class="card">
                    <?php if ($r['image']): ?><img src="<?= e($r['image']) ?>" class="card-img"><?php endif; ?>
                    <div class="card-body"><h4><?= e($r['title']) ?></h4></div>
                </a>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </article>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
