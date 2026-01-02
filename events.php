<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

$upcoming = fetchAll("SELECT * FROM events WHERE active = 1 AND event_date >= NOW() ORDER BY event_date LIMIT 20");
$past = fetchAll("SELECT * FROM events WHERE active = 1 AND event_date < NOW() ORDER BY event_date DESC LIMIT 6");
trackPageView('events');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - <?= e(SITE_NAME) ?></title>
    <meta name="description" content="Upcoming events and special broadcasts on <?= e(SITE_NAME) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="page-content">
        <div class="container">
            <div class="page-header">
                <span class="section-badge">Don't Miss Out</span>
                <h1 class="page-title">Events</h1>
                <p class="page-subtitle">Join us for exclusive live events, special broadcasts, and community gatherings</p>
            </div>
            
            <?php if (empty($upcoming)): ?>
            <div class="card" style="padding:4rem;text-align:center;margin-bottom:3rem">
                <div style="font-size:4rem;margin-bottom:1rem">📅</div>
                <h3>No Upcoming Events</h3>
                <p style="color:var(--text-muted)">Check back soon for new events, or follow us on social media for announcements!</p>
            </div>
            <?php else: ?>
            
            <!-- Featured/Next Event -->
            <?php $next = $upcoming[0]; ?>
            <div class="card" style="margin-bottom:3rem;overflow:hidden">
                <div class="grid grid-2" style="gap:0">
                    <?php if ($next['image']): ?>
                    <img src="<?= e($next['image']) ?>" alt="<?= e($next['title']) ?>" style="width:100%;height:100%;min-height:300px;object-fit:cover">
                    <?php else: ?>
                    <div style="background:linear-gradient(135deg,var(--primary),var(--accent));min-height:300px;display:flex;align-items:center;justify-content:center;font-size:5rem">🎉</div>
                    <?php endif; ?>
                    <div style="padding:2.5rem;display:flex;flex-direction:column;justify-content:center">
                        <span class="badge" style="background:var(--accent);color:white;width:fit-content;margin-bottom:1rem">Next Event</span>
                        <h2 style="font-size:2rem;margin-bottom:1rem"><?= e($next['title']) ?></h2>
                        <div style="display:flex;flex-wrap:wrap;gap:1.5rem;margin-bottom:1.5rem;color:var(--text-muted)">
                            <div style="display:flex;align-items:center;gap:0.5rem">
                                <span>📅</span>
                                <span><?= date('l, F j, Y', strtotime($next['event_date'])) ?></span>
                            </div>
                            <div style="display:flex;align-items:center;gap:0.5rem">
                                <span>🕐</span>
                                <span><?= date('g:i A', strtotime($next['event_date'])) ?></span>
                            </div>
                            <?php if ($next['location']): ?>
                            <div style="display:flex;align-items:center;gap:0.5rem">
                                <span>📍</span>
                                <span><?= e($next['location']) ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <p style="color:var(--text-muted);line-height:1.7;margin-bottom:1.5rem"><?= e($next['description']) ?></p>
                        <?php if ($next['link']): ?>
                        <a href="<?= e($next['link']) ?>" class="btn btn-primary" style="width:fit-content">Learn More →</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Other Upcoming Events -->
            <?php if (count($upcoming) > 1): ?>
            <h2 style="margin-bottom:2rem">More Upcoming Events</h2>
            <div class="grid grid-3" style="margin-bottom:3rem">
                <?php foreach (array_slice($upcoming, 1) as $ev): ?>
                <article class="card event-card">
                    <?php if ($ev['image']): ?>
                    <img src="<?= e($ev['image']) ?>" alt="<?= e($ev['title']) ?>" class="card-img">
                    <?php else: ?>
                    <div class="card-img" style="background:linear-gradient(135deg,var(--bg-elevated),var(--primary));display:flex;align-items:center;justify-content:center;font-size:3rem">🎉</div>
                    <?php endif; ?>
                    <div class="card-body">
                        <div class="event-date">
                            <span class="day"><?= date('d', strtotime($ev['event_date'])) ?></span>
                            <span class="month"><?= date('M', strtotime($ev['event_date'])) ?></span>
                        </div>
                        <h3><?= e($ev['title']) ?></h3>
                        <p><?= e(substr($ev['description'], 0, 100)) ?>...</p>
                        <?php if ($ev['location']): ?>
                        <div class="event-location">📍 <?= e($ev['location']) ?></div>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <?php endif; ?>
            
            <!-- Past Events -->
            <?php if (!empty($past)): ?>
            <div style="border-top:1px solid var(--border);padding-top:3rem;margin-top:2rem">
                <h2 style="margin-bottom:2rem;color:var(--text-muted)">Past Events</h2>
                <div class="grid grid-3">
                    <?php foreach ($past as $ev): ?>
                    <article class="card" style="opacity:0.7">
                        <?php if ($ev['image']): ?>
                        <img src="<?= e($ev['image']) ?>" alt="<?= e($ev['title']) ?>" class="card-img" style="filter:grayscale(50%)">
                        <?php endif; ?>
                        <div class="card-body">
                            <span style="font-size:0.85rem;color:var(--text-muted)"><?= date('M j, Y', strtotime($ev['event_date'])) ?></span>
                            <h3><?= e($ev['title']) ?></h3>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>
