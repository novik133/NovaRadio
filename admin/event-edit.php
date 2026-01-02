<?php $ev = isset($_GET['id']) ? fetch("SELECT * FROM events WHERE id = ?", [$_GET['id']]) : null; ?>
<div class="card">
    <form id="event-form" class="form">
        <input type="hidden" name="id" value="<?= $ev['id'] ?? '' ?>">
        <div class="form-group"><label>Title *</label><input type="text" name="title" value="<?= e($ev['title'] ?? '') ?>" required></div>
        <div class="form-row">
            <div class="form-group"><label>Date & Time *</label><input type="datetime-local" name="event_date" value="<?= $ev ? date('Y-m-d\TH:i', strtotime($ev['event_date'])) : '' ?>" required></div>
            <div class="form-group"><label>Location</label><input type="text" name="location" value="<?= e($ev['location'] ?? '') ?>"></div>
        </div>
        <div class="form-group"><label>Description</label><textarea name="description" rows="5"><?= e($ev['description'] ?? '') ?></textarea></div>
        <div class="form-group">
            <label>Image</label>
            <div class="image-upload">
                <input type="hidden" name="image" value="<?= e($ev['image'] ?? '') ?>">
                <img id="preview" src="<?= e($ev['image'] ?? 'assets/img/placeholder.png') ?>" class="preview-img">
                <input type="file" id="image-input" accept="image/*">
                <button type="button" class="btn btn-sm" onclick="document.getElementById('image-input').click()">Upload</button>
            </div>
        </div>
        <div class="form-group"><label class="checkbox"><input type="checkbox" name="active" <?= ($ev['active'] ?? 1) ? 'checked' : '' ?>> Active</label></div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Event</button>
            <a href="admin.php?page=events" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
<script>
document.getElementById('image-input').addEventListener('change', async (e) => {
    const file = e.target.files[0]; if (!file) return;
    const formData = new FormData(); formData.append('image', file);
    const res = await fetch('admin.php?ajax=upload', {method:'POST',body:formData});
    const data = await res.json();
    if (data.success) { document.querySelector('[name="image"]').value = data.url; document.getElementById('preview').src = data.url; }
});
document.getElementById('event-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const res = await fetch('admin.php?ajax=save-event', {
        method:'POST',headers:{'Content-Type':'application/json'},
        body:JSON.stringify({id:form.id.value||null,title:form.title.value,event_date:form.event_date.value,location:form.location.value,description:form.description.value,image:form.image.value,active:form.active.checked?1:0})
    });
    if ((await res.json()).success) window.location.href='admin.php?page=events';
});
</script>
