<?php
session_start();

// Check if installed
if (!file_exists('config.php')) {
    header('Location: install.php');
    exit;
}

require_once 'config.php';

if (!defined('INSTALLED') || !INSTALLED) {
    header('Location: install.php');
    exit;
}

require_once 'includes/functions.php';

$sliders = fetchAll("SELECT * FROM sliders WHERE active = 1 ORDER BY sort_order");
$shows = fetchAll("SELECT * FROM shows WHERE active = 1 ORDER BY name LIMIT 6");
$djs = fetchAll("SELECT * FROM djs WHERE active = 1 ORDER BY name LIMIT 4");
$events = fetchAll("SELECT * FROM events WHERE active = 1 AND event_date >= NOW() ORDER BY event_date LIMIT 3");
$posts = fetchAll("SELECT * FROM posts WHERE active = 1 ORDER BY created_at DESC LIMIT 3");
$podcasts = fetchAll("SELECT p.*, (SELECT COUNT(*) FROM episodes WHERE podcast_id = p.id) as episode_count FROM podcasts p WHERE p.active = 1 ORDER BY p.created_at DESC LIMIT 3");
$testimonials = fetchAll("SELECT * FROM testimonials WHERE active = 1 ORDER BY sort_order LIMIT 3");
trackPageView('home');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e(SITE_NAME) ?> - <?= e(getSetting('site_description', 'Your Home for Music')) ?></title>
    <meta name="description" content="<?= e(getSetting('site_description')) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <?= getSetting('custom_head_code') ?>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <?php if (!empty($sliders)): ?>
    <!-- Hero Slider -->
    <section class="slider">
        <?php foreach ($sliders as $i => $slide): ?>
        <div class="slide <?= $i === 0 ? 'active' : '' ?>">
            <div class="slide-bg" style="background-image:url('<?= e($slide['image']) ?>')"></div>
            <div class="slide-content">
                <div class="container">
                    <h1><?= e($slide['title']) ?></h1>
                    <p><?= e($slide['subtitle']) ?></p>
                    <?php if ($slide['link']): ?>
                    <a href="<?= e($slide['link']) ?>" class="btn btn-primary btn-lg">Learn More →</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php if (count($sliders) > 1): ?>
        <div class="slider-nav">
            <?php foreach ($sliders as $i => $s): ?>
            <button class="slider-dot <?= $i === 0 ? 'active' : '' ?>" data-slide="<?= $i ?>"></button>
            <?php endforeach; ?>
        </div>
        <button class="slider-prev">❮</button>
        <button class="slider-next">❯</button>
        <?php endif; ?>
    </section>
    <?php else: ?>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-badge">
                <span class="dot"></span>
                <span>Live Now</span>
            </div>
            <h1><?= e(getSetting('site_description', 'Your Home for the Best Music')) ?></h1>
            <p>24/7 non-stop music, live DJ sets, exclusive shows, and a community that loves music as much as you do.</p>
            <div class="hero-buttons">
                <button class="btn btn-primary btn-lg" onclick="togglePlay()">
                    <span>▶</span> Listen Live
                </button>
                <a href="schedule.php" class="btn btn-outline btn-lg">View Schedule</a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Stats -->
    <section class="section">
        <div class="container">
            <div class="stats-bar">
                <div class="stat-item">
                    <div class="stat-number" id="stat-listeners">0</div>
                    <div class="stat-label">Current Listeners</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?= count($shows) ?>+</div>
                    <div class="stat-label">Radio Shows</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?= count($djs) ?>+</div>
                    <div class="stat-label">Professional DJs</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Non-Stop Music</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Shows -->
    <?php if (!empty($shows)): ?>
    <section class="section section-dark">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">On Air</span>
                <h2 class="section-title">Featured Shows</h2>
                <p class="section-subtitle">Tune in to our most popular radio shows hosted by talented DJs</p>
            </div>
            <div class="grid grid-3">
                <?php foreach ($shows as $show): ?>
                <article class="card">
                    <?php if ($show['image']): ?>
                    <img src="<?= e($show['image']) ?>" alt="<?= e($show['name']) ?>" class="card-img">
                    <?php endif; ?>
                    <div class="card-body">
                        <span class="badge"><?= e($show['genre'] ?: 'Various') ?></span>
                        <h3><?= e($show['name']) ?></h3>
                        <p><?= e(substr($show['description'], 0, 100)) ?>...</p>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-4">
                <a href="shows.php" class="btn btn-outline">View All Shows →</a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Our DJs -->
    <?php if (!empty($djs)): ?>
    <section class="section">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">The Team</span>
                <h2 class="section-title">Meet Our DJs</h2>
                <p class="section-subtitle">The talented artists behind the music you love</p>
            </div>
            <div class="grid grid-4">
                <?php foreach ($djs as $dj): ?>
                <div class="card dj-card">
                    <img src="<?= e($dj['image'] ?: 'assets/img/dj-placeholder.png') ?>" alt="<?= e($dj['name']) ?>" class="avatar">
                    <h3><?= e($dj['name']) ?></h3>
                    <span class="role">DJ</span>
                    <?php if ($dj['social_links']): $social = json_decode($dj['social_links'], true); ?>
                    <div class="dj-social">
                        <?php if (!empty($social['instagram'])): ?><a href="<?= e($social['instagram']) ?>" target="_blank">IG</a><?php endif; ?>
                        <?php if (!empty($social['twitter'])): ?><a href="<?= e($social['twitter']) ?>" target="_blank">TW</a><?php endif; ?>
                        <?php if (!empty($social['soundcloud'])): ?><a href="<?= e($social['soundcloud']) ?>" target="_blank">SC</a><?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-4">
                <a href="djs.php" class="btn btn-outline">Meet All DJs →</a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Upcoming Events -->
    <?php if (!empty($events)): ?>
    <section class="section section-dark">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Don't Miss</span>
                <h2 class="section-title">Upcoming Events</h2>
                <p class="section-subtitle">Join us for exclusive live events and special broadcasts</p>
            </div>
            <div class="grid grid-3">
                <?php foreach ($events as $ev): ?>
                <article class="card event-card">
                    <?php if ($ev['image']): ?>
                    <img src="<?= e($ev['image']) ?>" alt="<?= e($ev['title']) ?>" class="card-img">
                    <?php endif; ?>
                    <div class="card-body">
                        <div class="event-date">
                            <span class="day"><?= date('d', strtotime($ev['event_date'])) ?></span>
                            <span class="month"><?= date('M', strtotime($ev['event_date'])) ?></span>
                        </div>
                        <h3><?= e($ev['title']) ?></h3>
                        <p><?= e(substr($ev['description'], 0, 80)) ?>...</p>
                        <?php if ($ev['location']): ?>
                        <div class="event-location">📍 <?= e($ev['location']) ?></div>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-4">
                <a href="events.php" class="btn btn-outline">View All Events →</a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Latest Blog Posts -->
    <?php if (!empty($posts)): ?>
    <section class="section">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">News & Updates</span>
                <h2 class="section-title">Latest from the Blog</h2>
                <p class="section-subtitle">Stay updated with the latest news, interviews, and music reviews</p>
            </div>
            <div class="grid grid-3">
                <?php foreach ($posts as $post): ?>
                <article class="card post-card">
                    <?php if ($post['image']): ?>
                    <a href="post.php?slug=<?= e($post['slug']) ?>">
                        <img src="<?= e($post['image']) ?>" alt="<?= e($post['title']) ?>" class="card-img">
                    </a>
                    <?php endif; ?>
                    <div class="card-body">
                        <div class="post-meta">
                            <span><?= date('M j, Y', strtotime($post['created_at'])) ?></span>
                        </div>
                        <h3><a href="post.php?slug=<?= e($post['slug']) ?>"><?= e($post['title']) ?></a></h3>
                        <p><?= e(substr($post['excerpt'] ?: strip_tags($post['content']), 0, 100)) ?>...</p>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-4">
                <a href="blog.php" class="btn btn-outline">Read More →</a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Recently Played -->
    <section class="section section-dark">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Now Playing</span>
                <h2 class="section-title">Recently Played</h2>
                <p class="section-subtitle">Check out what's been spinning on the airwaves</p>
            </div>
            <div id="history-list" class="history-list" style="max-width:700px;margin:0 auto">
                <p class="text-muted text-center">Loading track history...</p>
            </div>
            <div class="text-center mt-4">
                <a href="history.php" class="btn btn-outline">Full History →</a>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <?php if (!empty($testimonials)): ?>
    <section class="section">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Community</span>
                <h2 class="section-title">What Listeners Say</h2>
                <p class="section-subtitle">Join thousands of happy listeners worldwide</p>
            </div>
            <div class="grid grid-3">
                <?php foreach ($testimonials as $t): ?>
                <div class="card testimonial-card">
                    <?php if ($t['image']): ?>
                    <img src="<?= e($t['image']) ?>" alt="<?= e($t['name']) ?>" class="avatar">
                    <?php endif; ?>
                    <div class="testimonial-stars">
                        <?= str_repeat('⭐', $t['rating'] ?? 5) ?>
                    </div>
                    <p class="quote">"<?= e($t['content']) ?>"</p>
                    <div class="name"><?= e($t['name']) ?></div>
                    <?php if ($t['role']): ?>
                    <div class="role"><?= e($t['role']) ?></div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Newsletter -->
    <section class="section section-dark">
        <div class="container">
            <div class="newsletter-section">
                <h3>Stay in the Loop</h3>
                <p>Subscribe to our newsletter for exclusive updates, new shows, and special events.</p>
                <form class="newsletter-form" onsubmit="return subscribeNewsletter(event)">
                    <input type="email" id="newsletter-email" placeholder="Enter your email" required>
                    <button type="submit" class="btn btn-primary">Subscribe</button>
                </form>
                <div id="newsletter-msg" class="mt-2"></div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script>
    // Update stats
    function updateStats() {
        fetch('api.php?action=stats').then(r => r.json()).then(d => {
            if (d.listeners !== undefined) {
                document.getElementById('stat-listeners').textContent = d.listeners;
            }
        }).catch(() => {});
    }
    updateStats();
    setInterval(updateStats, 30000);

    // Newsletter
    function subscribeNewsletter(e) {
        e.preventDefault();
        const email = document.getElementById('newsletter-email').value;
        fetch('ajax.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=subscribe&email=' + encodeURIComponent(email)
        }).then(r => r.json()).then(d => {
            document.getElementById('newsletter-msg').innerHTML = d.success 
                ? '<span style="color:var(--success)">✓ Thanks for subscribing!</span>'
                : '<span style="color:var(--danger)">' + (d.error || 'Error') + '</span>';
            if (d.success) document.getElementById('newsletter-email').value = '';
        });
        return false;
    }

    // Slider
    <?php if (count($sliders) > 1): ?>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.slider-dot');
    
    function showSlide(n) {
        slides.forEach((s, i) => {
            s.classList.toggle('active', i === n);
            dots[i]?.classList.toggle('active', i === n);
        });
        currentSlide = n;
    }
    
    document.querySelector('.slider-next')?.addEventListener('click', () => {
        showSlide((currentSlide + 1) % slides.length);
    });
    document.querySelector('.slider-prev')?.addEventListener('click', () => {
        showSlide((currentSlide - 1 + slides.length) % slides.length);
    });
    dots.forEach((dot, i) => dot.addEventListener('click', () => showSlide(i)));
    setInterval(() => showSlide((currentSlide + 1) % slides.length), 6000);
    <?php endif; ?>
    </script>
</body>
</html>
