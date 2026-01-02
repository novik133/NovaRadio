<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

$station = getDefaultStation();
$queue = fetchAll("SELECT * FROM request_queue WHERE station_id = ? AND status = 'queued' ORDER BY position LIMIT 20", [$station['id'] ?? 0]);
trackPageView('queue');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Queue - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container page-content">
        <h1 class="page-title">🎵 Live Request Queue</h1>
        <p class="text-center text-muted">Songs requested by listeners - updated live!</p>
        
        <div class="queue-container">
            <div class="queue-add">
                <h3>Add Your Request</h3>
                <form id="queue-form" class="queue-form">
                    <input type="text" name="artist" placeholder="Artist" required>
                    <input type="text" name="title" placeholder="Song Title" required>
                    <input type="text" name="name" placeholder="Your Name (optional)">
                    <button type="submit" class="btn btn-primary">Add to Queue</button>
                </form>
                <div id="queue-message"></div>
            </div>
            
            <div class="queue-list">
                <h3>Current Queue</h3>
                <div id="queue-items">
                    <?php if (empty($queue)): ?>
                    <p class="text-muted">Queue is empty. Be the first to request!</p>
                    <?php else: ?>
                    <?php foreach ($queue as $i => $item): ?>
                    <div class="queue-item">
                        <span class="queue-position"><?= $i + 1 ?></span>
                        <div class="queue-info">
                            <strong><?= e($item['title']) ?></strong>
                            <span><?= e($item['artist']) ?></span>
                        </div>
                        <span class="queue-requester">by <?= e($item['requested_by'] ?: 'Anonymous') ?></span>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
    <script>
    document.getElementById('queue-form').onsubmit = async (e) => {
        e.preventDefault();
        const fd = new FormData(e.target);
        fd.append('action', 'add_to_queue');
        fd.append('station_id', '<?= $station['id'] ?? 0 ?>');
        const res = await fetch('ajax.php', { method: 'POST', body: fd });
        const data = await res.json();
        const msg = document.getElementById('queue-message');
        if (data.success) {
            msg.innerHTML = '<div class="alert alert-success">Added to queue at position #' + data.position + '!</div>';
            e.target.reset();
            setTimeout(() => location.reload(), 1500);
        } else {
            msg.innerHTML = '<div class="alert alert-danger">' + (data.error || 'Error') + '</div>';
        }
    };
    </script>
</body>
</html>
