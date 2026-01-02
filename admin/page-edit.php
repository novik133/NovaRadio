<?php $p = isset($_GET['id']) ? fetch("SELECT * FROM pages WHERE id = ?", [$_GET['id']]) : null; ?>
<div class="card">
    <form id="page-form" class="form">
        <input type="hidden" name="id" value="<?= $p['id'] ?? '' ?>">
        <div class="form-row">
            <div class="form-group"><label>Title *</label><input type="text" name="title" value="<?= e($p['title'] ?? '') ?>" required></div>
            <div class="form-group"><label>Slug *</label><input type="text" name="slug" value="<?= e($p['slug'] ?? '') ?>" required></div>
        </div>
        <div class="form-group"><label>Meta Description</label><input type="text" name="meta_description" value="<?= e($p['meta_description'] ?? '') ?>"></div>
        <div class="form-group"><label>Content</label><textarea name="content" rows="15"><?= e($p['content'] ?? '') ?></textarea></div>
        <div class="form-group"><label class="checkbox"><input type="checkbox" name="active" <?= ($p['active'] ?? 1) ? 'checked' : '' ?>> Active</label></div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Page</button>
            <a href="admin.php?page=pages" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
<script>
document.getElementById('page-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const res = await fetch('admin.php?ajax=save-page', {
        method:'POST',headers:{'Content-Type':'application/json'},
        body:JSON.stringify({id:form.id.value||null,title:form.title.value,slug:form.slug.value,meta_description:form.meta_description.value,content:form.content.value,active:form.active.checked?1:0})
    });
    if ((await res.json()).success) window.location.href='admin.php?page=pages';
});
</script>
