<?php $s = isset($_GET['id']) ? fetch("SELECT * FROM sponsors WHERE id = ?", [$_GET['id']]) : null; ?>
<div class="card">
    <div class="card-header"><h3><?= $s ? 'Edit' : 'Add' ?> Sponsor</h3></div>
    <form id="item-form" class="form">
        <input type="hidden" name="id" value="<?= $s['id'] ?? '' ?>">
        <div class="row-2">
            <div class="form-group"><label>Name *</label><input type="text" name="name" value="<?= e($s['name'] ?? '') ?>" required></div>
            <div class="form-group"><label>Website</label><input type="url" name="website" value="<?= e($s['website'] ?? '') ?>"></div>
        </div>
        <div class="form-group"><label>Description</label><textarea name="description" rows="3"><?= e($s['description'] ?? '') ?></textarea></div>
        <div class="row-3">
            <div class="form-group"><label>Tier</label><select name="tier"><option value="platinum" <?= ($s['tier'] ?? '') == 'platinum' ? 'selected' : '' ?>>Platinum</option><option value="gold" <?= ($s['tier'] ?? '') == 'gold' ? 'selected' : '' ?>>Gold</option><option value="silver" <?= ($s['tier'] ?? '') == 'silver' ? 'selected' : '' ?>>Silver</option><option value="bronze" <?= ($s['tier'] ?? '') == 'bronze' ? 'selected' : '' ?>>Bronze</option><option value="partner" <?= ($s['tier'] ?? 'partner') == 'partner' ? 'selected' : '' ?>>Partner</option></select></div>
            <div class="form-group"><label>Sort Order</label><input type="number" name="sort_order" value="<?= $s['sort_order'] ?? 0 ?>"></div>
            <div class="form-group"><label>Status</label><select name="active"><option value="1" <?= ($s['active'] ?? 1) ? 'selected' : '' ?>>Active</option><option value="0" <?= isset($s['active']) && !$s['active'] ? 'selected' : '' ?>>Hidden</option></select></div>
        </div>
        <div class="form-group"><label>Logo</label><div class="image-upload" data-field="logo"><input type="hidden" name="logo" value="<?= e($s['logo'] ?? '') ?>"><?php if (!empty($s['logo'])): ?><img src="<?= e($s['logo']) ?>"><?php endif; ?><button type="button" class="btn btn-sm" onclick="uploadImage('logo')">Upload</button></div></div>
        <div class="form-actions"><button type="submit" class="btn btn-primary">Save</button><a href="admin.php?page=sponsors" class="btn btn-outline">Cancel</a></div>
    </form>
</div>
<script>document.getElementById('item-form').onsubmit=async e=>{e.preventDefault();const f=new FormData(e.target);const d=Object.fromEntries(f);await fetch('admin.php?ajax=save-sponsor',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(d)});location.href='admin.php?page=sponsors';};</script>
