<?php $d = isset($_GET['id']) ? fetch("SELECT * FROM downloads WHERE id = ?", [$_GET['id']]) : null; $djs = fetchAll("SELECT id, name FROM djs WHERE active = 1 ORDER BY name"); ?>
<div class="card">
    <div class="card-header"><h3><?= $d ? 'Edit' : 'Add' ?> Download</h3></div>
    <form id="item-form" class="form">
        <input type="hidden" name="id" value="<?= $d['id'] ?? '' ?>">
        <div class="form-group"><label>Title *</label><input type="text" name="title" value="<?= e($d['title'] ?? '') ?>" required></div>
        <div class="form-group"><label>Description</label><textarea name="description" rows="3"><?= e($d['description'] ?? '') ?></textarea></div>
        <div class="form-group"><label>File URL *</label><input type="url" name="file_url" value="<?= e($d['file_url'] ?? '') ?>" required></div>
        <div class="row-3">
            <div class="form-group"><label>Category</label><input type="text" name="category" value="<?= e($d['category'] ?? '') ?>" placeholder="e.g., Mix, Podcast"></div>
            <div class="form-group"><label>DJ</label><select name="dj_id"><option value="">-- None --</option><?php foreach ($djs as $dj): ?><option value="<?= $dj['id'] ?>" <?= ($d['dj_id'] ?? '') == $dj['id'] ? 'selected' : '' ?>><?= e($dj['name']) ?></option><?php endforeach; ?></select></div>
            <div class="form-group"><label>Status</label><select name="active"><option value="1" <?= ($d['active'] ?? 1) ? 'selected' : '' ?>>Active</option><option value="0" <?= isset($d['active']) && !$d['active'] ? 'selected' : '' ?>>Hidden</option></select></div>
        </div>
        <div class="form-group"><label>Cover Image</label><div class="image-upload" data-field="image"><input type="hidden" name="image" value="<?= e($d['image'] ?? '') ?>"><?php if (!empty($d['image'])): ?><img src="<?= e($d['image']) ?>"><?php endif; ?><button type="button" class="btn btn-sm" onclick="uploadImage('image')">Upload</button></div></div>
        <div class="form-actions"><button type="submit" class="btn btn-primary">Save</button><a href="admin.php?page=downloads" class="btn btn-outline">Cancel</a></div>
    </form>
</div>
<script>document.getElementById('item-form').onsubmit=async e=>{e.preventDefault();const f=new FormData(e.target);const d=Object.fromEntries(f);await fetch('admin.php?ajax=save-download',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(d)});location.href='admin.php?page=downloads';};</script>
