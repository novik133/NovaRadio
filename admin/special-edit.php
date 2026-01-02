<?php
requireLogin();
$id = $_GET['id'] ?? null;
$special = $id ? fetch("SELECT * FROM special_broadcasts WHERE id = ?", [$id]) : null;
$stations = getStations(false);
$djs = fetchAll("SELECT * FROM djs WHERE active = 1 ORDER BY name");
?>
<div class="admin-header">
    <h1><?= $special ? 'Edit' : 'Add' ?> Special Broadcast</h1>
    <a href="admin.php?page=specials" class="btn">← Back</a>
</div>
<form id="special-form" class="form-card">
    <input type="hidden" name="id" value="<?= $special['id'] ?? '' ?>">
    <div class="form-group"><label>Title</label><input type="text" name="title" value="<?= e($special['title'] ?? '') ?>" required></div>
    <div class="form-group"><label>Description</label><textarea name="description" rows="3"><?= e($special['description'] ?? '') ?></textarea></div>
    <div class="form-row">
        <div class="form-group">
            <label>Station</label>
            <select name="station_id">
                <option value="">All Stations</option>
                <?php foreach ($stations as $s): ?>
                <option value="<?= $s['id'] ?>" <?= ($special['station_id'] ?? '') == $s['id'] ? 'selected' : '' ?>><?= e($s['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>DJ</label>
            <select name="dj_id">
                <option value="">None</option>
                <?php foreach ($djs as $d): ?>
                <option value="<?= $d['id'] ?>" <?= ($special['dj_id'] ?? '') == $d['id'] ? 'selected' : '' ?>><?= e($d['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group"><label>Start Time</label><input type="datetime-local" name="start_time" value="<?= $special['start_time'] ? date('Y-m-d\TH:i', strtotime($special['start_time'])) : '' ?>" required></div>
        <div class="form-group"><label>End Time</label><input type="datetime-local" name="end_time" value="<?= $special['end_time'] ? date('Y-m-d\TH:i', strtotime($special['end_time'])) : '' ?>" required></div>
    </div>
    <div class="form-group">
        <label>Image</label>
        <div class="image-upload">
            <input type="text" name="image" id="image" value="<?= e($special['image'] ?? '') ?>">
            <input type="file" id="image-file" accept="image/*" onchange="uploadImage(this, 'image')">
            <label for="image-file" class="btn">Upload</label>
        </div>
    </div>
    <label class="checkbox"><input type="checkbox" name="is_live" value="1" <?= ($special['is_live'] ?? 0) ? 'checked' : '' ?>> Currently Live</label>
    <button type="submit" class="btn btn-primary">Save Broadcast</button>
</form>
<script>
document.getElementById('special-form').onsubmit = async e => {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(e.target));
    data.is_live = data.is_live ? 1 : 0;
    const res = await fetch('admin.php?ajax=save-special', { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(data) });
    if ((await res.json()).success) location.href = 'admin.php?page=specials';
};
</script>
