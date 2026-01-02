<?php
requireLogin();
$stations = getStations(false);
$stationId = $_GET['station'] ?? ($stations[0]['id'] ?? 0);
$queue = fetchAll("SELECT * FROM request_queue WHERE station_id = ? ORDER BY status = 'queued' DESC, position", [$stationId]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['status'])) {
        update('request_queue', ['status' => $_POST['status']], 'id = ?', [(int)$_POST['id']]);
        redirect("admin.php?page=queue&station=$stationId");
    }
    if (isset($_POST['delete'])) {
        delete('request_queue', 'id = ?', [(int)$_POST['id']]);
        redirect("admin.php?page=queue&station=$stationId");
    }
    if (isset($_POST['clear'])) {
        delete('request_queue', "station_id = ? AND status != 'queued'", [$stationId]);
        redirect("admin.php?page=queue&station=$stationId");
    }
}
$queue = fetchAll("SELECT * FROM request_queue WHERE station_id = ? ORDER BY status = 'queued' DESC, position", [$stationId]);
?>
<div class="admin-header">
    <h1>Request Queue</h1>
    <div>
        <?php if (count($stations) > 1): ?>
        <select onchange="location.href='admin.php?page=queue&station='+this.value">
            <?php foreach ($stations as $s): ?>
            <option value="<?= $s['id'] ?>" <?= $stationId == $s['id'] ? 'selected' : '' ?>><?= e($s['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <?php endif; ?>
        <form method="post" style="display:inline"><button name="clear" class="btn btn-danger" onclick="return confirm('Clear played items?')">Clear Played</button></form>
    </div>
</div>

<table class="table">
    <thead><tr><th>#</th><th>Song</th><th>Requested By</th><th>Status</th><th>Time</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($queue as $q): ?>
    <tr>
        <td><?= $q['position'] ?></td>
        <td><strong><?= e($q['title']) ?></strong><br><small><?= e($q['artist']) ?></small></td>
        <td><?= e($q['requested_by'] ?: 'Anonymous') ?></td>
        <td><span class="badge badge-<?= $q['status'] === 'queued' ? 'warning' : ($q['status'] === 'playing' ? 'danger' : 'success') ?>"><?= ucfirst($q['status']) ?></span></td>
        <td><?= date('g:i A', strtotime($q['created_at'])) ?></td>
        <td>
            <form method="post" style="display:inline">
                <input type="hidden" name="id" value="<?= $q['id'] ?>">
                <select name="status" onchange="this.form.submit()">
                    <option value="queued" <?= $q['status'] === 'queued' ? 'selected' : '' ?>>Queued</option>
                    <option value="playing" <?= $q['status'] === 'playing' ? 'selected' : '' ?>>Playing</option>
                    <option value="played" <?= $q['status'] === 'played' ? 'selected' : '' ?>>Played</option>
                    <option value="skipped" <?= $q['status'] === 'skipped' ? 'selected' : '' ?>>Skipped</option>
                </select>
                <button name="delete" class="btn btn-sm btn-danger">×</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
