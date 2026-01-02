<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

$success = false;
$error = '';
$stations = getStations();
$defaultStation = getDefaultStation();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $artist = trim($_POST['artist'] ?? '');
    $title = trim($_POST['title'] ?? '');
    
    if (!$artist || !$title) {
        $error = 'Please enter both artist and song title.';
    } else {
        insert('requests', [
            'listener_name' => trim($_POST['name'] ?? '') ?: 'Anonymous',
            'song_artist' => $artist,
            'song_title' => $title,
            'message' => trim($_POST['message'] ?? ''),
            'station_id' => $_POST['station_id'] ?? $defaultStation['id'] ?? null
        ]);
        $success = true;
    }
}
trackPageView('request');

// Get recent requests
$recentRequests = fetchAll("SELECT * FROM requests WHERE status IN ('approved','played') ORDER BY created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request a Song - <?= e(SITE_NAME) ?></title>
    <meta name="description" content="Request your favorite songs on <?= e(SITE_NAME) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="page-content">
        <div class="container">
            <div class="page-header">
                <span class="section-badge">🎵 Request</span>
                <h1 class="page-title">Request a Song</h1>
                <p class="page-subtitle">Want to hear your favorite track? Submit a request and we'll try to play it!</p>
            </div>
            
            <div class="grid grid-2" style="gap:3rem;max-width:1000px;margin:0 auto">
                <div>
                    <?php if ($success): ?>
                    <div class="card" style="padding:3rem;text-align:center">
                        <div style="font-size:4rem;margin-bottom:1rem">🎉</div>
                        <h2 style="margin-bottom:0.5rem">Request Submitted!</h2>
                        <p style="color:var(--text-muted);margin-bottom:2rem">Thanks for your request! Our DJs will review it and try to play it soon.</p>
                        <a href="request.php" class="btn btn-primary">Request Another Song</a>
                    </div>
                    <?php else: ?>
                    
                    <div class="card request-form" style="max-width:none">
                        <h3 style="margin-bottom:1.5rem">Submit Your Request</h3>
                        
                        <?php if ($error): ?>
                        <div class="alert alert-danger"><?= e($error) ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="form-group">
                                <label>Your Name</label>
                                <input type="text" name="name" placeholder="Anonymous" value="<?= e($_POST['name'] ?? '') ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>Artist *</label>
                                <input type="text" name="artist" required placeholder="e.g. Daft Punk" value="<?= e($_POST['artist'] ?? '') ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>Song Title *</label>
                                <input type="text" name="title" required placeholder="e.g. Around the World" value="<?= e($_POST['title'] ?? '') ?>">
                            </div>
                            
                            <?php if (count($stations) > 1): ?>
                            <div class="form-group">
                                <label>Station</label>
                                <select name="station_id">
                                    <?php foreach ($stations as $st): ?>
                                    <option value="<?= $st['id'] ?>" <?= ($defaultStation && $st['id'] == $defaultStation['id']) ? 'selected' : '' ?>><?= e($st['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endif; ?>
                            
                            <div class="form-group">
                                <label>Message / Shoutout (optional)</label>
                                <textarea name="message" rows="3" placeholder="Add a dedication or shoutout..."><?= e($_POST['message'] ?? '') ?></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg" style="width:100%">
                                🎵 Submit Request
                            </button>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div>
                    <div class="card" style="padding:1.5rem;margin-bottom:1.5rem">
                        <h3 style="margin-bottom:1rem">📋 Request Guidelines</h3>
                        <ul style="color:var(--text-muted);padding-left:1.25rem;line-height:2">
                            <li>Requests are reviewed by our DJs</li>
                            <li>Not all requests can be played</li>
                            <li>Popular songs have higher chances</li>
                            <li>Be patient - it may take some time</li>
                            <li>Keep it family-friendly</li>
                        </ul>
                    </div>
                    
                    <?php if (!empty($recentRequests)): ?>
                    <div class="card" style="padding:1.5rem">
                        <h3 style="margin-bottom:1rem">✅ Recently Played Requests</h3>
                        <div style="display:flex;flex-direction:column;gap:0.75rem">
                            <?php foreach ($recentRequests as $req): ?>
                            <div style="padding:0.75rem;background:var(--bg-glass);border-radius:var(--radius-sm)">
                                <strong><?= e($req['song_artist']) ?></strong>
                                <span style="color:var(--text-muted)"> - <?= e($req['song_title']) ?></span>
                                <div style="font-size:0.8rem;color:var(--text-muted);margin-top:0.25rem">
                                    Requested by <?= e($req['listener_name'] ?: 'Anonymous') ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="text-center mt-3">
                        <a href="dedications.php" class="btn btn-outline">💝 Send a Dedication Instead</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>
