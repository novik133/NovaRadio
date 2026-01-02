<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

$contests = fetchAll("SELECT * FROM contests WHERE active = 1 AND end_date > NOW() ORDER BY end_date");
trackPageView('contests');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contests - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <h1 class="page-title">Contests & Giveaways</h1>
        <?php if (empty($contests)): ?>
        <p class="text-center text-muted">No active contests at the moment. Check back soon!</p>
        <?php else: ?>
        <div class="grid grid-2">
            <?php foreach ($contests as $c): ?>
            <div class="card contest-card">
                <?php if ($c['image']): ?><img src="<?= e($c['image']) ?>" class="card-img"><?php endif; ?>
                <div class="card-body">
                    <h3><?= e($c['title']) ?></h3>
                    <p><?= e(substr($c['description'], 0, 150)) ?>...</p>
                    <div class="contest-prize">🎁 Prize: <?= e($c['prize']) ?></div>
                    <div class="contest-deadline">⏰ Ends: <?= date('M j, Y', strtotime($c['end_date'])) ?></div>
                    <a href="contest.php?id=<?= $c['id'] ?>" class="btn btn-primary">Enter Now</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
