<?php
$stats = [
    'stations' => fetch("SELECT COUNT(*) as c FROM stations WHERE active = 1")['c'] ?? 0,
    'shows' => fetch("SELECT COUNT(*) as c FROM shows")['c'] ?? 0,
    'djs' => fetch("SELECT COUNT(*) as c FROM djs")['c'] ?? 0,
    'events' => fetch("SELECT COUNT(*) as c FROM events WHERE active = 1")['c'] ?? 0,
    'messages' => fetch("SELECT COUNT(*) as c FROM messages WHERE is_read = 0")['c'] ?? 0,
    'requests' => fetch("SELECT COUNT(*) as c FROM requests WHERE status = 'pending'")['c'] ?? 0,
];

// Get listeners from all stations
$totalListeners = 0;
foreach (getStations() as $st) {
    try {
        $np = getNowPlaying($st);
        $totalListeners += $np['listeners']['current'] ?? 0;
    } catch (Exception $e) {}
}

$recentMessages = fetchAll("SELECT * FROM messages ORDER BY created_at DESC LIMIT 5");
$recentRequests = fetchAll("SELECT * FROM requests WHERE status = 'pending' ORDER BY created_at DESC LIMIT 5");
?>
<div class="stats-grid">
    <div class="stat-card"><div class="stat-icon">👥</div><div class="stat-info"><span class="stat-value"><?= $totalListeners ?></span><span class="stat-label">Total Listeners</span></div></div>
    <div class="stat-card"><div class="stat-icon">📻</div><div class="stat-info"><span class="stat-value"><?= $stats['stations'] ?></span><span class="stat-label">Stations</span></div></div>
    <div class="stat-card"><div class="stat-icon">🎵</div><div class="stat-info"><span class="stat-value"><?= $stats['shows'] ?></span><span class="stat-label">Shows</span></div></div>
    <div class="stat-card"><div class="stat-icon">🎧</div><div class="stat-info"><span class="stat-value"><?= $stats['djs'] ?></span><span class="stat-label">DJs</span></div></div>
    <div class="stat-card"><div class="stat-icon">✉️</div><div class="stat-info"><span class="stat-value"><?= $stats['messages'] ?></span><span class="stat-label">Unread</span></div></div>
    <div class="stat-card"><div class="stat-icon">🎶</div><div class="stat-info"><span class="stat-value"><?= $stats['requests'] ?></span><span class="stat-label">Requests</span></div></div>
</div>

<div class="grid-2 mt-2">
    <div class="card">
        <div class="card-header"><h3>Recent Messages</h3><a href="admin.php?page=messages" class="btn btn-sm">View All</a></div>
        <div class="card-body">
            <?php if (empty($recentMessages)): ?><p class="text-muted">No messages</p>
            <?php else: foreach ($recentMessages as $msg): ?>
                <div class="list-item"><strong><?= e($msg['name']) ?></strong><span class="text-muted"> - <?= e(substr($msg['message'], 0, 50)) ?>...</span></div>
            <?php endforeach; endif; ?>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3>Song Requests</h3><a href="admin.php?page=requests" class="btn btn-sm">View All</a></div>
        <div class="card-body">
            <?php if (empty($recentRequests)): ?><p class="text-muted">No pending requests</p>
            <?php else: foreach ($recentRequests as $req): ?>
                <div class="list-item"><strong><?= e($req['song_artist']) ?> - <?= e($req['song_title']) ?></strong><span class="text-muted"> by <?= e($req['listener_name']) ?></span></div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</div>

<div class="card mt-2">
    <div class="card-header"><h3>Quick Actions</h3></div>
    <div class="card-body">
        <a href="admin.php?page=station-edit" class="btn btn-primary">Add Station</a>
        <a href="admin.php?page=show-edit" class="btn btn-primary">Add Show</a>
        <a href="admin.php?page=dj-edit" class="btn btn-primary">Add DJ</a>
        <a href="admin.php?page=event-edit" class="btn btn-outline">Add Event</a>
    </div>
</div>
