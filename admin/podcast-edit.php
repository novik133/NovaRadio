<?php
requireLogin();
$id = $_GET['id'] ?? null;
$podcast = $id ? fetch("SELECT * FROM podcasts WHERE id = ?", [$id]) : null;
?>
<div class="admin-header">
    <h1><?= $podcast ? 'Edit' : 'Add' ?> Podcast</h1>
    <a href="admin.php?page=podcasts" class="btn">← Back</a>
</div>
<form id="podcast-form" class="form-card">
    <input type="hidden" name="id" value="<?= $podcast['id'] ?? '' ?>">
    <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" value="<?= e($podcast['title'] ?? '') ?>" required>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Author</label>
            <input type="text" name="author" value="<?= e($podcast['author'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Category</label>
            <input type="text" name="category" value="<?= e($podcast['category'] ?? '') ?>">
        </div>
    </div>
    <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="4"><?= e($podcast['description'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
        <label>Cover Image</label>
        <div class="image-upload">
            <input type="text" name="image" id="image" value="<?= e($podcast['image'] ?? '') ?>">
            <input type="file" id="image-file" accept="image/*" onchange="uploadImage(this, 'image')">
            <label for="image-file" class="btn">Upload</label>
        </div>
    </div>
    <label class="checkbox"><input type="checkbox" name="active" value="1" <?= ($podcast['active'] ?? 1) ? 'checked' : '' ?>> Active</label>
    <button type="submit" class="btn btn-primary">Save Podcast</button>
</form>
<script>
document.getElementById('podcast-form').onsubmit = async e => {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(e.target));
    data.active = data.active ? 1 : 0;
    const res = await fetch('admin.php?ajax=save-podcast', { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(data) });
    if ((await res.json()).success) location.href = 'admin.php?page=podcasts';
};
</script>
