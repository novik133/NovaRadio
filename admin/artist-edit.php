<?php
requireLogin();
$id = $_GET['id'] ?? null;
$artist = $id ? fetch("SELECT * FROM artists WHERE id = ?", [$id]) : null;
$social = $artist ? json_decode($artist['social_links'], true) : [];
?>
<div class="admin-header">
    <h1><?= $artist ? 'Edit' : 'Add' ?> Artist</h1>
    <a href="admin.php?page=artists" class="btn">← Back</a>
</div>
<form id="artist-form" class="form-card">
    <input type="hidden" name="id" value="<?= $artist['id'] ?? '' ?>">
    <div class="form-row">
        <div class="form-group flex-2"><label>Name</label><input type="text" name="name" value="<?= e($artist['name'] ?? '') ?>" required onkeyup="generateSlug(this, 'slug')"></div>
        <div class="form-group"><label>Slug</label><input type="text" name="slug" id="slug" value="<?= e($artist['slug'] ?? '') ?>" required></div>
    </div>
    <div class="form-group"><label>Genre</label><input type="text" name="genre" value="<?= e($artist['genre'] ?? '') ?>"></div>
    <div class="form-group"><label>Bio</label><textarea name="bio" rows="4"><?= e($artist['bio'] ?? '') ?></textarea></div>
    <div class="form-group"><label>Website</label><input type="url" name="website" value="<?= e($artist['website'] ?? '') ?>"></div>
    <div class="form-group">
        <label>Image</label>
        <div class="image-upload">
            <input type="text" name="image" id="image" value="<?= e($artist['image'] ?? '') ?>">
            <input type="file" id="image-file" accept="image/*" onchange="uploadImage(this, 'image')">
            <label for="image-file" class="btn">Upload</label>
        </div>
    </div>
    <h3>Social Links</h3>
    <div class="form-row">
        <div class="form-group"><label>Facebook</label><input type="url" name="social_facebook" value="<?= e($social['facebook'] ?? '') ?>"></div>
        <div class="form-group"><label>Instagram</label><input type="url" name="social_instagram" value="<?= e($social['instagram'] ?? '') ?>"></div>
    </div>
    <div class="form-row">
        <div class="form-group"><label>Twitter</label><input type="url" name="social_twitter" value="<?= e($social['twitter'] ?? '') ?>"></div>
        <div class="form-group"><label>Spotify</label><input type="url" name="social_spotify" value="<?= e($social['spotify'] ?? '') ?>"></div>
    </div>
    <label class="checkbox"><input type="checkbox" name="active" value="1" <?= ($artist['active'] ?? 1) ? 'checked' : '' ?>> Active</label>
    <button type="submit" class="btn btn-primary">Save Artist</button>
</form>
<script>
document.getElementById('artist-form').onsubmit = async e => {
    e.preventDefault();
    const fd = new FormData(e.target);
    const data = { id: fd.get('id'), name: fd.get('name'), slug: fd.get('slug'), genre: fd.get('genre'), bio: fd.get('bio'), website: fd.get('website'), image: fd.get('image'), active: fd.get('active') ? 1 : 0, social_links: { facebook: fd.get('social_facebook'), instagram: fd.get('social_instagram'), twitter: fd.get('social_twitter'), spotify: fd.get('social_spotify') } };
    const res = await fetch('admin.php?ajax=save-artist', { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(data) });
    if ((await res.json()).success) location.href = 'admin.php?page=artists';
};
function generateSlug(input, target) { document.getElementById(target).value = input.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, ''); }
</script>
