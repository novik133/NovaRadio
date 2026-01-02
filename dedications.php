<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

if (getSetting('dedications_enabled', '1') !== '1') {
    header('Location: index.php');
    exit;
}

$stations = getStations();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stationId = (int)($_POST['station_id'] ?? 0);
    $fromName = trim($_POST['from_name'] ?? '');
    $toName = trim($_POST['to_name'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $artist = trim($_POST['artist'] ?? '');
    $title = trim($_POST['title'] ?? '');
    
    if (!$fromName || !$toName) {
        $error = 'From and To names are required';
    } else {
        insert('dedications', [
            'station_id' => $stationId ?: null,
            'from_name' => $fromName,
            'to_name' => $toName,
            'message' => $message,
            'song_artist' => $artist,
            'song_title' => $title
        ]);
        $success = 'Your dedication has been submitted!';
    }
}

trackPageView('dedications');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dedications - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <h1 class="page-title">Send a Dedication</h1>
        <p class="text-center text-muted">Dedicate a song to someone special!</p>
        
        <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>
        
        <form method="post" class="form-card" style="max-width:600px;margin:2rem auto">
            <?php if (count($stations) > 1): ?>
            <div class="form-group">
                <label>Station</label>
                <select name="station_id">
                    <?php foreach ($stations as $s): ?>
                    <option value="<?= $s['id'] ?>"><?= e($s['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            <div class="form-row">
                <div class="form-group"><label>From *</label><input type="text" name="from_name" required placeholder="Your name"></div>
                <div class="form-group"><label>To *</label><input type="text" name="to_name" required placeholder="Recipient's name"></div>
            </div>
            <div class="form-group"><label>Message</label><textarea name="message" rows="3" placeholder="Your dedication message..."></textarea></div>
            <div class="form-row">
                <div class="form-group"><label>Song Artist</label><input type="text" name="artist" placeholder="Optional"></div>
                <div class="form-group"><label>Song Title</label><input type="text" name="title" placeholder="Optional"></div>
            </div>
            <button type="submit" class="btn btn-primary">Send Dedication</button>
        </form>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
