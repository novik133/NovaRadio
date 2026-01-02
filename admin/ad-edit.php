<?php
requireLogin();
$id = $_GET['id'] ?? null;
$ad = $id ? fetch("SELECT * FROM ads WHERE id = ?", [$id]) : null;
?>
<div class="admin-header">
    <h1><?= $ad ? 'Edit' : 'Add' ?> Ad</h1>
    <a href="admin.php?page=ads" class="btn">← Back</a>
</div>
<form id="ad-form" class="form-card">
    <input type="hidden" name="id" value="<?= $ad['id'] ?? '' ?>">
    <div class="form-row">
        <div class="form-group flex-2">
            <label>Name</label>
            <input type="text" name="name" value="<?= e($ad['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Position</label>
            <select name="position">
                <option value="header" <?= ($ad['position'] ?? '') === 'header' ? 'selected' : '' ?>>Header</option>
                <option value="sidebar" <?= ($ad['position'] ?? 'sidebar') === 'sidebar' ? 'selected' : '' ?>>Sidebar</option>
                <option value="footer" <?= ($ad['position'] ?? '') === 'footer' ? 'selected' : '' ?>>Footer</option>
                <option value="popup" <?= ($ad['position'] ?? '') === 'popup' ? 'selected' : '' ?>>Popup</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label>Image</label>
        <div class="image-upload">
            <input type="text" name="image" id="image" value="<?= e($ad['image'] ?? '') ?>">
            <input type="file" id="image-file" accept="image/*" onchange="uploadImage(this, 'image')">
            <label for="image-file" class="btn">Upload</label>
        </div>
    </div>
    <div class="form-group">
        <label>Link URL</label>
        <input type="text" name="link" value="<?= e($ad['link'] ?? '') ?>">
    </div>
    <div class="form-group">
        <label>Custom HTML (optional, overrides image)</label>
        <textarea name="content" rows="4"><?= e($ad['content'] ?? '') ?></textarea>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Start Date</label>
            <input type="datetime-local" name="starts_at" value="<?= $ad['starts_at'] ? date('Y-m-d\TH:i', strtotime($ad['starts_at'])) : '' ?>">
        </div>
        <div class="form-group">
            <label>End Date</label>
            <input type="datetime-local" name="ends_at" value="<?= $ad['ends_at'] ? date('Y-m-d\TH:i', strtotime($ad['ends_at'])) : '' ?>">
        </div>
    </div>
    <label class="checkbox"><input type="checkbox" name="active" value="1" <?= ($ad['active'] ?? 1) ? 'checked' : '' ?>> Active</label>
    <button type="submit" class="btn btn-primary">Save Ad</button>
</form>
<script>
document.getElementById('ad-form').onsubmit = async e => {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(e.target));
    data.active = data.active ? 1 : 0;
    const res = await fetch('admin.php?ajax=save-ad', { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(data) });
    if ((await res.json()).success) location.href = 'admin.php?page=ads';
};
</script>
