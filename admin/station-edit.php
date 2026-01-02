<?php $st = isset($_GET['id']) ? fetch("SELECT * FROM stations WHERE id = ?", [$_GET['id']]) : null; ?>
<div class="card">
    <form id="station-form" class="form form-wide">
        <input type="hidden" name="id" value="<?= $st['id'] ?? '' ?>">
        
        <div class="form-section">
            <h3>Basic Info</h3>
            <div class="form-row">
                <div class="form-group"><label>Station Name *</label><input type="text" name="name" value="<?= e($st['name'] ?? '') ?>" required></div>
                <div class="form-group"><label>Slug *</label><input type="text" name="slug" value="<?= e($st['slug'] ?? '') ?>" required><small>URL-friendly identifier</small></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Genre</label><input type="text" name="genre" value="<?= e($st['genre'] ?? '') ?>"></div>
                <div class="form-group"><label>Sort Order</label><input type="number" name="sort_order" value="<?= $st['sort_order'] ?? 0 ?>"></div>
            </div>
            <div class="form-group"><label>Description</label><textarea name="description" rows="3"><?= e($st['description'] ?? '') ?></textarea></div>
            <div class="form-group">
                <label>Station Image</label>
                <div class="image-upload">
                    <input type="hidden" name="image" value="<?= e($st['image'] ?? '') ?>">
                    <img id="preview" src="<?= e($st['image'] ?? 'assets/img/placeholder.png') ?>" class="preview-img">
                    <input type="file" id="image-input" accept="image/*">
                    <button type="button" class="btn btn-sm" onclick="document.getElementById('image-input').click()">Upload</button>
                </div>
            </div>
        </div>
        
        <div class="form-section">
            <h3>AzuraCast Connection</h3>
            <div class="form-group"><label>AzuraCast URL</label><input type="url" name="azuracast_url" value="<?= e($st['azuracast_url'] ?? '') ?>" placeholder="https://panel.example.com"></div>
            <div class="form-row">
                <div class="form-group"><label>API Key</label><input type="text" name="api_key" value="<?= e($st['api_key'] ?? '') ?>"></div>
                <div class="form-group"><label>Station ID</label><input type="number" name="station_id" value="<?= $st['station_id'] ?? 1 ?>"></div>
            </div>
            <div class="form-group"><label>Stream URL *</label><input type="url" name="stream_url" value="<?= e($st['stream_url'] ?? '') ?>" placeholder="https://panel.example.com/listen/station/radio.mp3" required></div>
        </div>
        
        <div class="form-section">
            <h3>Options</h3>
            <div class="form-row">
                <div class="form-group"><label class="checkbox"><input type="checkbox" name="is_default" <?= ($st['is_default'] ?? 0) ? 'checked' : '' ?>> Default Station</label></div>
                <div class="form-group"><label class="checkbox"><input type="checkbox" name="active" <?= ($st['active'] ?? 1) ? 'checked' : '' ?>> Active</label></div>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Station</button>
            <a href="admin.php?page=stations" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
<script>
document.getElementById('image-input').addEventListener('change', async (e) => {
    const file = e.target.files[0]; if (!file) return;
    const fd = new FormData(); fd.append('image', file);
    const res = await fetch('admin.php?ajax=upload', {method:'POST',body:fd});
    const data = await res.json();
    if (data.success) { document.querySelector('[name="image"]').value = data.url; document.getElementById('preview').src = data.url; }
});
document.getElementById('station-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const f = e.target;
    const res = await fetch('admin.php?ajax=save-station', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({
            id: f.id.value || null, name: f.name.value, slug: f.slug.value, description: f.description.value,
            genre: f.genre.value, image: f.image.value, azuracast_url: f.azuracast_url.value,
            api_key: f.api_key.value, station_id: f.station_id.value, stream_url: f.stream_url.value,
            is_default: f.is_default.checked ? 1 : 0, active: f.active.checked ? 1 : 0, sort_order: f.sort_order.value
        })
    });
    if ((await res.json()).success) window.location.href = 'admin.php?page=stations';
});
</script>
