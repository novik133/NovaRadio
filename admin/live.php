<?php
requireLogin();
$stations = getStations(false);
$stationId = $_GET['station'] ?? ($stations[0]['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['toggle_live'])) {
        $current = fetch("SELECT * FROM live_status WHERE station_id = ?", [$stationId]);
        if ($current) {
            update('live_status', ['is_live' => $current['is_live'] ? 0 : 1, 'started_at' => $current['is_live'] ? null : date('Y-m-d H:i:s')], 'station_id = ?', [$stationId]);
        } else {
            insert('live_status', ['station_id' => $stationId, 'is_live' => 1, 'started_at' => date('Y-m-d H:i:s')]);
        }
        redirect("admin.php?page=live&station=$stationId");
    }
    if (isset($_POST['update_live'])) {
        $data = ['title' => $_POST['title'], 'show_id' => $_POST['show_id'] ?: null, 'dj_id' => $_POST['dj_id'] ?: null];
        $exists = fetch("SELECT id FROM live_status WHERE station_id = ?", [$stationId]);
        if ($exists) update('live_status', $data, 'station_id = ?', [$stationId]);
        else insert('live_status', array_merge($data, ['station_id' => $stationId]));
        redirect("admin.php?page=live&station=$stationId&msg=updated");
    }
}

$live = fetch("SELECT * FROM live_status WHERE station_id = ?", [$stationId]);
$shows = fetchAll("SELECT * FROM shows WHERE active = 1 ORDER BY name");
$djs = fetchAll("SELECT * FROM djs WHERE active = 1 ORDER BY name");
?>
<div class="admin-header">
    <h1>Live Status</h1>
    <?php if (count($stations) > 1): ?>
    <select onchange="location.href='admin.php?page=live&station='+this.value">
        <?php foreach ($stations as $s): ?>
        <option value="<?= $s['id'] ?>" <?= $stationId == $s['id'] ? 'selected' : '' ?>><?= e($s['name']) ?></option>
        <?php endforeach; ?>
    </select>
    <?php endif; ?>
</div>

<?php if (isset($_GET['msg'])): ?><div class="alert alert-success">Updated!</div><?php endif; ?>

<div class="card">
    <div class="card-header">
        Current Status: 
        <?php if ($live && $live['is_live']): ?>
        <span class="badge badge-danger">🔴 LIVE</span>
        <?php if ($live['started_at']): ?> since <?= date('g:i A', strtotime($live['started_at'])) ?><?php endif; ?>
        <?php else: ?>
        <span class="badge badge-secondary">OFF AIR</span>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <form method="post" style="margin-bottom:1rem">
            <button name="toggle_live" class="btn <?= ($live && $live['is_live']) ? 'btn-danger' : 'btn-success' ?>">
                <?= ($live && $live['is_live']) ? 'Go Off Air' : 'Go Live' ?>
            </button>
        </form>
        <form method="post">
            <div class="form-group"><label>Live Show Title</label><input type="text" name="title" value="<?= e($live['title'] ?? '') ?>" placeholder="e.g., Friday Night Mix"></div>
            <div class="form-row">
                <div class="form-group">
                    <label>Show</label>
                    <select name="show_id">
                        <option value="">None</option>
                        <?php foreach ($shows as $sh): ?>
                        <option value="<?= $sh['id'] ?>" <?= ($live['show_id'] ?? '') == $sh['id'] ? 'selected' : '' ?>><?= e($sh['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>DJ</label>
                    <select name="dj_id">
                        <option value="">None</option>
                        <?php foreach ($djs as $d): ?>
                        <option value="<?= $d['id'] ?>" <?= ($live['dj_id'] ?? '') == $d['id'] ? 'selected' : '' ?>><?= e($d['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button name="update_live" class="btn btn-primary">Update Info</button>
        </form>
    </div>
</div>
