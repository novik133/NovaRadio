<?php
$headerMenu = getMenuItems('header');
$stations = getStations();
$currentStation = $currentStation ?? getDefaultStation();
$logo = logoUrl();
$live = fetch("SELECT * FROM live_status WHERE station_id = ? AND is_live = 1", [$currentStation['id'] ?? 0]);
?>
<nav class="navbar" id="navbar">
    <div class="container">
        <a href="index.php" class="logo">
            <?php if ($logo): ?>
                <img src="<?= e($logo) ?>" alt="<?= e(siteName()) ?>">
            <?php else: ?>
                <span class="logo-text"><?= e(logoText() ?: siteName()) ?></span>
            <?php endif; ?>
        </a>
        
        <div class="nav-links" id="nav-links">
            <?php if (!empty($headerMenu)): ?>
                <?php foreach ($headerMenu as $item): ?>
                <a href="<?= e($item['url']) ?>" target="<?= e($item['target']) ?>"><?= e($item['label']) ?></a>
                <?php endforeach; ?>
            <?php else: ?>
                <a href="index.php">Home</a>
                <a href="schedule.php">Schedule</a>
                <a href="shows.php">Shows</a>
                <a href="djs.php">DJs</a>
                <a href="blog.php">Blog</a>
                <a href="podcasts.php">Podcasts</a>
                <a href="events.php">Events</a>
                <a href="chat.php">Chat</a>
                <a href="request.php">Request</a>
                <a href="contact.php">Contact</a>
            <?php endif; ?>
        </div>
        
        <div class="nav-actions">
            <button id="theme-toggle" class="theme-toggle" aria-label="Toggle theme">🌙</button>
            <a href="request.php" class="nav-btn" aria-label="Request a song">
                <span>🎵</span>
                <span>Request</span>
            </a>
            <button class="nav-toggle" id="nav-toggle" aria-label="Menu">☰</button>
        </div>
    </div>
</nav>

<?php if (count($stations) > 1): ?>
<div class="station-bar">
    <div class="container">
        <div class="station-tabs">
            <?php foreach ($stations as $st): ?>
            <button class="station-tab <?= ($currentStation && $st['id'] == $currentStation['id']) ? 'active' : '' ?>" 
                    data-station="<?= $st['id'] ?>" 
                    data-stream="<?= e($st['stream_url']) ?>"
                    data-name="<?= e($st['name']) ?>">
                <?= e($st['name']) ?>
            </button>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<div id="player-bar" data-stream="<?= e($currentStation['stream_url'] ?? '') ?>" data-station="<?= $currentStation['id'] ?? '' ?>">
    <canvas id="visualizer"></canvas>
    <div class="container player-content">
        <div class="now-playing">
            <img id="np-artwork" src="assets/img/placeholder.png" alt="Album Art">
            <span class="live-badge <?= $live ? 'pulse' : '' ?>"><?= $live ? '● LIVE' : 'ON AIR' ?></span>
            <div class="track-info">
                <span id="np-artist">Loading...</span>
                <span id="np-title">Connecting to stream...</span>
            </div>
        </div>
        
        <div class="player-controls">
            <button class="player-action reaction-btn" onclick="reactToTrack('like')" title="Like this track">❤️</button>
            <button id="play-btn" class="play-btn" aria-label="Play">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
            </button>
            <button class="player-action reaction-btn" onclick="shareTrack()" title="Share">📤</button>
        </div>
        
        <div class="volume-control">
            <span>🔊</span>
            <input type="range" id="volume" min="0" max="100" value="80" aria-label="Volume">
        </div>
        
        <div class="listeners">
            <span class="dot"></span>
            <span id="listener-count">0</span> listening
        </div>
    </div>
</div>

<script>
// Navbar scroll effect
window.addEventListener('scroll', () => {
    document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 50);
});

// Mobile nav toggle
document.getElementById('nav-toggle')?.addEventListener('click', () => {
    document.getElementById('nav-links').classList.toggle('show');
});
</script>
