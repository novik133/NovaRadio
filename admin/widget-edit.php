<?php $w = isset($_GET['id']) ? fetch("SELECT * FROM widgets WHERE id = ?", [$_GET['id']]) : null; ?>
<div class="card">
    <form id="widget-form" class="form">
        <input type="hidden" name="id" value="<?= $w['id'] ?? '' ?>">
        <div class="form-row">
            <div class="form-group">
                <label>Location *</label>
                <select name="location" required>
                    <option value="footer_1" <?= ($w['location'] ?? '') == 'footer_1' ? 'selected' : '' ?>>Footer Column 1 (Left)</option>
                    <option value="footer_2" <?= ($w['location'] ?? '') == 'footer_2' ? 'selected' : '' ?>>Footer Column 2 (Middle)</option>
                    <option value="footer_3" <?= ($w['location'] ?? '') == 'footer_3' ? 'selected' : '' ?>>Footer Column 3 (Right)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Widget Type</label>
                <select name="widget_type">
                    <option value="text" <?= ($w['widget_type'] ?? '') == 'text' ? 'selected' : '' ?>>Plain Text</option>
                    <option value="html" <?= ($w['widget_type'] ?? '') == 'html' ? 'selected' : '' ?>>HTML</option>
                </select>
            </div>
        </div>
        <div class="form-group"><label>Title</label><input type="text" name="title" value="<?= e($w['title'] ?? '') ?>"></div>
        <div class="form-group"><label>Content</label><textarea name="content" rows="8"><?= e($w['content'] ?? '') ?></textarea></div>
        <div class="form-row">
            <div class="form-group"><label>Sort Order</label><input type="number" name="sort_order" value="<?= $w['sort_order'] ?? 0 ?>"></div>
            <div class="form-group"><label class="checkbox"><input type="checkbox" name="active" <?= ($w['active'] ?? 1) ? 'checked' : '' ?>> Active</label></div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Widget</button>
            <a href="admin.php?page=widgets" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
<script>
document.getElementById('widget-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const f = e.target;
    const res = await fetch('admin.php?ajax=save-widget', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({id:f.id.value||null, location:f.location.value, title:f.title.value, content:f.content.value, widget_type:f.widget_type.value, sort_order:f.sort_order.value, active:f.active.checked?1:0})
    });
    if ((await res.json()).success) window.location.href = 'admin.php?page=widgets';
});
</script>
