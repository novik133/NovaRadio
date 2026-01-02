<?php $s = isset($_GET['id']) ? fetch("SELECT * FROM sliders WHERE id = ?", [$_GET['id']]) : null; ?>
<div class="card">
    <form id="slider-form" class="form">
        <input type="hidden" name="id" value="<?= $s['id'] ?? '' ?>">
        <div class="form-group"><label>Title</label><input type="text" name="title" value="<?= e($s['title'] ?? '') ?>"></div>
        <div class="form-group"><label>Subtitle</label><input type="text" name="subtitle" value="<?= e($s['subtitle'] ?? '') ?>"></div>
        <div class="form-group">
            <label>Image</label>
            <div class="image-upload">
                <input type="hidden" name="image" value="<?= e($s['image'] ?? '') ?>">
                <img id="preview" src="<?= e($s['image'] ?? 'assets/img/placeholder.png') ?>" class="preview-img-wide">
                <input type="file" id="image-input" accept="image/*">
                <button type="button" class="btn btn-sm" onclick="document.getElementById('image-input').click()">Upload</button>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Link URL</label><input type="url" name="link" value="<?= e($s['link'] ?? '') ?>"></div>
            <div class="form-group"><label>Sort Order</label><input type="number" name="sort_order" value="<?= $s['sort_order'] ?? 0 ?>"></div>
        </div>
        <div class="form-group"><label class="checkbox"><input type="checkbox" name="active" <?= ($s['active'] ?? 1) ? 'checked' : '' ?>> Active</label></div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Slider</button>
            <a href="admin.php?page=sliders" class="btn btn-outline">Cancel</a>
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
document.getElementById('slider-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const res = await fetch('admin.php?ajax=save-slider', {
        method:'POST',headers:{'Content-Type':'application/json'},
        body:JSON.stringify({id:form.id.value||null,title:form.title.value,subtitle:form.subtitle.value,image:form.image.value,link:form.link.value,sort_order:form.sort_order.value,active:form.active.checked?1:0})
    });
    if ((await res.json()).success) window.location.href='admin.php?page=sliders';
});
</script>
