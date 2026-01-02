<?php
requireLogin();
$id = $_GET['id'] ?? null;
$gallery = $id ? fetch("SELECT * FROM galleries WHERE id = ?", [$id]) : null;
?>
<div class="admin-header">
    <h1><?= $gallery ? 'Edit' : 'Add' ?> Gallery</h1>
    <a href="admin.php?page=galleries" class="btn">← Back</a>
</div>
<form id="gallery-form" class="form-card">
    <input type="hidden" name="id" value="<?= $gallery['id'] ?? '' ?>">
    <div class="form-row">
        <div class="form-group flex-2">
            <label>Title</label>
            <input type="text" name="title" value="<?= e($gallery['title'] ?? '') ?>" required onkeyup="generateSlug(this, 'slug')">
        </div>
        <div class="form-group">
            <label>Slug</label>
            <input type="text" name="slug" id="slug" value="<?= e($gallery['slug'] ?? '') ?>" required>
        </div>
    </div>
    <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="3"><?= e($gallery['description'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
        <label>Cover Image</label>
        <div class="image-upload">
            <input type="text" name="cover_image" id="cover_image" value="<?= e($gallery['cover_image'] ?? '') ?>">
            <input type="file" id="cover-file" accept="image/*" onchange="uploadImage(this, 'cover_image')">
            <label for="cover-file" class="btn">Upload</label>
        </div>
    </div>
    <label class="checkbox"><input type="checkbox" name="active" value="1" <?= ($gallery['active'] ?? 1) ? 'checked' : '' ?>> Active</label>
    <button type="submit" class="btn btn-primary">Save Gallery</button>
</form>
<script>
document.getElementById('gallery-form').onsubmit = async e => {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(e.target));
    data.active = data.active ? 1 : 0;
    const res = await fetch('admin.php?ajax=save-gallery', { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(data) });
    if ((await res.json()).success) location.href = 'admin.php?page=galleries';
};
function generateSlug(input, target) {
    document.getElementById(target).value = input.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
}
</script>
