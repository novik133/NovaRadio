<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

$shows = fetchAll("SELECT s.*, st.name as station_name FROM shows s LEFT JOIN stations st ON s.station_id = st.id WHERE s.active = 1 ORDER BY s.name");
trackPageView('shows');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shows - <?= e(SITE_NAME) ?></title>
    <meta name="description" content="Explore all radio shows on <?= e(SITE_NAME) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="page-content">
        <div class="container">
            <div class="page-header">
                <span class="section-badge">On Air</span>
                <h1 class="page-title">Our Shows</h1>
                <p class="page-subtitle">Discover our diverse lineup of radio shows, each bringing unique vibes and music</p>
            </div>
            
            <?php if (empty($shows)): ?>
            <p class="text-center text-muted">No shows available at the moment.</p>
            <?php else: ?>
            <div class="grid grid-3">
                <?php foreach ($shows as $show): ?>
                <article class="card">
                    <?php if ($show['image']): ?>
                    <img src="<?= e($show['image']) ?>" alt="<?= e($show['name']) ?>" class="card-img">
                    <?php else: ?>
                    <div class="card-img" style="background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;font-size:3rem">🎵</div>
                    <?php endif; ?>
                    <div class="card-body">
                        <div class="card-meta">
                            <span class="badge"><?= e($show['genre'] ?: 'Various') ?></span>
                            <?php if ($show['station_name']): ?>
                            <span><?= e($show['station_name']) ?></span>
                            <?php endif; ?>
                        </div>
                        <h3><?= e($show['name']) ?></h3>
                        <p><?= e(substr($show['description'], 0, 150)) ?><?= strlen($show['description']) > 150 ? '...' : '' ?></p>
                        <button class="btn btn-outline" style="margin-top:1rem;width:100%" onclick="showDetails(<?= $show['id'] ?>)">View Details</button>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
