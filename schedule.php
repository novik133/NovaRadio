<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

$schedule = fetchAll("
    SELECT s.*, sh.name as show_name, sh.image as show_image, sh.genre, d.name as dj_name, d.image as dj_image
    FROM schedule s 
    LEFT JOIN shows sh ON s.show_id = sh.id 
    LEFT JOIN djs d ON s.dj_id = d.id 
    ORDER BY s.day_of_week, s.start_time
");
$days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
$currentDay = date('N');
trackPageView('schedule');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule - <?= e(SITE_NAME) ?></title>
    <meta name="description" content="Weekly programming schedule for <?= e(SITE_NAME) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="page-content">
        <div class="container">
            <div class="page-header">
                <span class="section-badge">Programming</span>
                <h1 class="page-title">Weekly Schedule</h1>
                <p class="page-subtitle">Check out when your favorite shows are on air</p>
            </div>
            
            <div class="schedule-container">
                <div class="schedule-tabs">
                    <?php foreach ($days as $i => $day): ?>
                    <button class="tab-btn <?= ($i + 1) == $currentDay ? 'active' : '' ?>" data-day="<?= $i + 1 ?>">
                        <?= substr($day, 0, 3) ?>
                        <?php if (($i + 1) == $currentDay): ?><span style="display:block;font-size:0.7rem;color:var(--primary)">Today</span><?php endif; ?>
                    </button>
                    <?php endforeach; ?>
                </div>

                <?php foreach ($days as $i => $day): ?>
                <div class="schedule-day <?= ($i + 1) == $currentDay ? 'active' : '' ?>" data-day="<?= $i + 1 ?>">
                    <?php 
                    $daySchedule = array_filter($schedule, fn($s) => $s['day_of_week'] == ($i + 1));
                    if (empty($daySchedule)): ?>
                        <div class="text-center" style="padding:3rem">
                            <p class="text-muted">No shows scheduled for <?= $day ?></p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($daySchedule as $slot): 
                            $isNow = ($i + 1) == $currentDay && 
                                     date('H:i:s') >= $slot['start_time'] && 
                                     date('H:i:s') <= $slot['end_time'];
                        ?>
                        <div class="schedule-item <?= $isNow ? 'is-live' : '' ?>" style="<?= $isNow ? 'border-color:var(--accent);background:rgba(244,63,94,0.05)' : '' ?>">
                            <div class="schedule-time">
                                <?= date('g:i A', strtotime($slot['start_time'])) ?>
                                <span style="display:block;font-size:0.8rem;color:var(--text-muted)"><?= date('g:i A', strtotime($slot['end_time'])) ?></span>
                            </div>
                            <div class="schedule-show">
                                <strong><?= e($slot['show_name'] ?: 'TBA') ?></strong>
                                <span>
                                    <?php if ($slot['dj_name']): ?>with <?= e($slot['dj_name']) ?><?php endif; ?>
                                    <?php if ($slot['genre']): ?> • <?= e($slot['genre']) ?><?php endif; ?>
                                </span>
                            </div>
                            <?php if ($isNow): ?>
                            <span class="schedule-live">● LIVE NOW</span>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-4">
                <p class="text-muted">All times shown in your local timezone</p>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
