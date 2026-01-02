<?php $t = isset($_GET['id']) ? fetch("SELECT * FROM testimonials WHERE id = ?", [$_GET['id']]) : null; ?>
<div class="card">
    <div class="card-header"><h3><?= $t ? 'Edit' : 'Add' ?> Testimonial</h3></div>
    <form id="item-form" class="form">
        <input type="hidden" name="id" value="<?= $t['id'] ?? '' ?>">
        <div class="row-2">
            <div class="form-group"><label>Name *</label><input type="text" name="name" value="<?= e($t['name'] ?? '') ?>" required></div>
            <div class="form-group"><label>Role/Title</label><input type="text" name="role" value="<?= e($t['role'] ?? '') ?>"></div>
        </div>
        <div class="form-group"><label>Content *</label><textarea name="content" rows="4" required><?= e($t['content'] ?? '') ?></textarea></div>
        <div class="row-3">
            <div class="form-group"><label>Rating</label><select name="rating"><?php for($i=5;$i>=1;$i--): ?><option value="<?= $i ?>" <?= ($t['rating'] ?? 5) == $i ? 'selected' : '' ?>><?= str_repeat('⭐', $i) ?></option><?php endfor; ?></select></div>
            <div class="form-group"><label>Sort Order</label><input type="number" name="sort_order" value="<?= $t['sort_order'] ?? 0 ?>"></div>
            <div class="form-group"><label>Status</label><select name="active"><option value="1" <?= ($t['active'] ?? 1) ? 'selected' : '' ?>>Active</option><option value="0" <?= isset($t['active']) && !$t['active'] ? 'selected' : '' ?>>Hidden</option></select></div>
        </div>
        <div class="form-group"><label>Image</label><div class="image-upload" data-field="image"><input type="hidden" name="image" value="<?= e($t['image'] ?? '') ?>"><?php if (!empty($t['image'])): ?><img src="<?= e($t['image']) ?>"><?php endif; ?><button type="button" class="btn btn-sm" onclick="uploadImage('image')">Upload</button></div></div>
        <div class="form-actions"><button type="submit" class="btn btn-primary">Save</button><a href="admin.php?page=testimonials" class="btn btn-outline">Cancel</a></div>
    </form>
</div>
<script>document.getElementById('item-form').onsubmit=async e=>{e.preventDefault();const f=new FormData(e.target);const d=Object.fromEntries(f);await fetch('admin.php?ajax=save-testimonial',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(d)});location.href='admin.php?page=testimonials';};</script>
