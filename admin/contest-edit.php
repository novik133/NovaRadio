<?php
requireLogin();
$id = $_GET['id'] ?? null;
$contest = $id ? fetch("SELECT * FROM contests WHERE id = ?", [$id]) : null;
?>
<div class="admin-header">
    <h1><?= $contest ? 'Edit' : 'Add' ?> Contest</h1>
    <a href="admin.php?page=contests" class="btn">← Back</a>
</div>
<form id="contest-form" class="form-card">
    <input type="hidden" name="id" value="<?= $contest['id'] ?? '' ?>">
    <div class="form-group"><label>Title</label><input type="text" name="title" value="<?= e($contest['title'] ?? '') ?>" required></div>
    <div class="form-group"><label>Description</label><textarea name="description" rows="4"><?= e($contest['description'] ?? '') ?></textarea></div>
    <div class="form-group"><label>Prize</label><input type="text" name="prize" value="<?= e($contest['prize'] ?? '') ?>"></div>
    <div class="form-group"><label>Rules</label><textarea name="rules" rows="4"><?= e($contest['rules'] ?? '') ?></textarea></div>
    <div class="form-group">
        <label>Image</label>
        <div class="image-upload">
            <input type="text" name="image" id="image" value="<?= e($contest['image'] ?? '') ?>">
            <input type="file" id="image-file" accept="image/*" onchange="uploadImage(this, 'image')">
            <label for="image-file" class="btn">Upload</label>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group"><label>Start Date</label><input type="datetime-local" name="start_date" value="<?= $contest['start_date'] ? date('Y-m-d\TH:i', strtotime($contest['start_date'])) : '' ?>"></div>
        <div class="form-group"><label>End Date</label><input type="datetime-local" name="end_date" value="<?= $contest['end_date'] ? date('Y-m-d\TH:i', strtotime($contest['end_date'])) : '' ?>"></div>
    </div>
    <label class="checkbox"><input type="checkbox" name="active" value="1" <?= ($contest['active'] ?? 1) ? 'checked' : '' ?>> Active</label>
    <button type="submit" class="btn btn-primary">Save Contest</button>
</form>
<script>
document.getElementById('contest-form').onsubmit = async e => {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(e.target));
    data.active = data.active ? 1 : 0;
    const res = await fetch('admin.php?ajax=save-contest', { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(data) });
    if ((await res.json()).success) location.href = 'admin.php?page=contests';
};
</script>
