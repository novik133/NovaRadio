<?php 
$djData = isset($_GET['id']) ? fetch("SELECT * FROM djs WHERE id = ?", [$_GET['id']]) : null;
$social = json_decode($djData['social_links'] ?? '{}', true) ?: [];
?>
<div class="card">
    <div class="card-header"><h3><?= $djData ? 'Edit' : 'Add' ?> DJ</h3></div>
    <form id="dj-form" class="form">
        <input type="hidden" name="id" value="<?= $djData['id'] ?? '' ?>">
        
        <h4>Basic Info</h4>
        <div class="form-row">
            <div class="form-group flex-2">
                <label>Name *</label>
                <input type="text" name="name" value="<?= e($djData['name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Image</label>
                <div class="image-upload">
                    <input type="hidden" name="image" value="<?= e($djData['image'] ?? '') ?>">
                    <input type="file" id="image-input" accept="image/*">
                    <button type="button" class="btn btn-sm" onclick="document.getElementById('image-input').click()">Upload</button>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Bio</label>
            <textarea name="bio" rows="4"><?= e($djData['bio'] ?? '') ?></textarea>
        </div>
        
        <h4>DJ Panel Login</h4>
        <div class="form-row">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= e($djData['email'] ?? '') ?>" placeholder="For DJ panel login">
            </div>
            <div class="form-group">
                <label>Password <?= $djData ? '(leave blank to keep)' : '' ?></label>
                <input type="password" name="password" placeholder="<?= $djData ? '••••••••' : 'Set password' ?>">
            </div>
        </div>
        
        <h4>AzuraCast Integration</h4>
        <div class="form-row">
            <div class="form-group">
                <label>AzuraCast DJ/Streamer ID</label>
                <input type="number" name="azuracast_dj_id" value="<?= e($djData['azuracast_dj_id'] ?? '') ?>" placeholder="From AzuraCast">
            </div>
            <div class="form-group">
                <label>AzuraCast Username</label>
                <input type="text" name="azuracast_username" value="<?= e($djData['azuracast_username'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>AzuraCast Password</label>
                <input type="text" name="azuracast_password" value="<?= e($djData['azuracast_password'] ?? '') ?>">
            </div>
        </div>
        
        <h4>Permissions</h4>
        <div class="form-row">
            <label class="checkbox"><input type="checkbox" name="can_stream" <?= ($djData['can_stream'] ?? 1) ? 'checked' : '' ?>> Can Stream Live</label>
            <label class="checkbox"><input type="checkbox" name="can_upload" <?= ($djData['can_upload'] ?? 1) ? 'checked' : '' ?>> Can Upload Media</label>
            <label class="checkbox"><input type="checkbox" name="can_playlist" <?= ($djData['can_playlist'] ?? 1) ? 'checked' : '' ?>> Can Manage Playlists</label>
            <label class="checkbox"><input type="checkbox" name="active" <?= ($djData['active'] ?? 1) ? 'checked' : '' ?>> Active</label>
        </div>
        
        <h4>Social Links</h4>
        <div class="form-row">
            <div class="form-group"><label>Instagram</label><input type="url" name="instagram" value="<?= e($social['instagram'] ?? '') ?>"></div>
            <div class="form-group"><label>SoundCloud</label><input type="url" name="soundcloud" value="<?= e($social['soundcloud'] ?? '') ?>"></div>
            <div class="form-group"><label>Mixcloud</label><input type="url" name="mixcloud" value="<?= e($social['mixcloud'] ?? '') ?>"></div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save DJ</button>
            <a href="admin.php?page=djs" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
<script>
document.getElementById('image-input').addEventListener('change', async (e) => {
    const file = e.target.files[0];
    if (!file) return;
    const formData = new FormData();
    formData.append('image', file);
    const res = await fetch('admin.php?ajax=upload', {method: 'POST', body: formData});
    const data = await res.json();
    if (data.success) document.querySelector('[name="image"]').value = data.url;
});

document.getElementById('dj-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const data = {
        id: form.id.value || null,
        name: form.name.value,
        bio: form.bio.value,
        image: form.image.value,
        email: form.email.value,
        password: form.password.value,
        azuracast_dj_id: form.azuracast_dj_id.value,
        azuracast_username: form.azuracast_username.value,
        azuracast_password: form.azuracast_password.value,
        can_stream: form.can_stream.checked ? 1 : 0,
        can_upload: form.can_upload.checked ? 1 : 0,
        can_playlist: form.can_playlist.checked ? 1 : 0,
        active: form.active.checked ? 1 : 0,
        social_links: {
            instagram: form.instagram.value,
            soundcloud: form.soundcloud.value,
            mixcloud: form.mixcloud.value
        }
    };
    const res = await fetch('admin.php?ajax=save-dj', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
    });
    if ((await res.json()).success) window.location.href = 'admin.php?page=djs';
});
</script>
