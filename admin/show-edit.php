<?php 
$show = isset($_GET['id']) ? fetch("SELECT * FROM shows WHERE id = ?", [$_GET['id']]) : null;
$stations = getStations(false);
?>
<div class="card">
    <form id="show-form" class="form">
        <input type="hidden" name="id" value="<?= $show['id'] ?? '' ?>">
        <div class="form-row">
            <div class="form-group"><label>Name *</label><input type="text" name="name" value="<?= e($show['name'] ?? '') ?>" required></div>
            <div class="form-group">
                <label>Station</label>
                <select name="station_id">
                    <option value="">All Stations</option>
                    <?php foreach ($stations as $st): ?>
                    <option value="<?= $st['id'] ?>" <?= ($show['station_id'] ?? '') == $st['id'] ? 'selected' : '' ?>><?= e($st['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group"><label>Genre</label><input type="text" name="genre" value="<?= e($show['genre'] ?? '') ?>"></div>
        <div class="form-group"><label>Description</label><textarea name="description" rows="4"><?= e($show['description'] ?? '') ?></textarea></div>
        <div class="form-group">
            <label>Image</label>
            <div class="image-upload">
                <input type="hidden" name="image" value="<?= e($show['image'] ?? '') ?>">
                <img id="preview" src="<?= e($show['image'] ?? 'assets/img/placeholder.png') ?>" class="preview-img">
                <input type="file" id="image-input" accept="image/*">
                <button type="button" class="btn btn-sm" onclick="document.getElementById('image-input').click()">Upload</button>
            </div>
        </div>
        <div class="form-group"><label class="checkbox"><input type="checkbox" name="active" <?= ($show['active'] ?? 1) ? 'checked' : '' ?>> Active</label></div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Show</button>
            <a href="admin.php?page=shows" class="btn btn-outline">Cancel</a>
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
document.getElementById('show-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const f = e.target;
    const res = await fetch('admin.php?ajax=save-show', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({id:f.id.value||null, station_id:f.station_id.value||null, name:f.name.value, genre:f.genre.value, description:f.description.value, image:f.image.value, active:f.active.checked?1:0})
    });
    if ((await res.json()).success) window.location.href = 'admin.php?page=shows';
});
</script>
