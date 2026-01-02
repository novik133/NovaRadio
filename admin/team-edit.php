<?php $t = isset($_GET['id']) ? fetch("SELECT * FROM team WHERE id = ?", [$_GET['id']]) : null; $social = $t ? json_decode($t['social_links'] ?? '{}', true) : []; ?>
<div class="card">
    <div class="card-header"><h3><?= $t ? 'Edit' : 'Add' ?> Team Member</h3></div>
    <form id="item-form" class="form">
        <input type="hidden" name="id" value="<?= $t['id'] ?? '' ?>">
        <div class="row-2">
            <div class="form-group"><label>Name *</label><input type="text" name="name" value="<?= e($t['name'] ?? '') ?>" required></div>
            <div class="form-group"><label>Role/Title</label><input type="text" name="role" value="<?= e($t['role'] ?? '') ?>"></div>
        </div>
        <div class="form-group"><label>Bio</label><textarea name="bio" rows="3"><?= e($t['bio'] ?? '') ?></textarea></div>
        <div class="row-2">
            <div class="form-group"><label>Email</label><input type="email" name="email" value="<?= e($t['email'] ?? '') ?>"></div>
            <div class="form-group"><label>Sort Order</label><input type="number" name="sort_order" value="<?= $t['sort_order'] ?? 0 ?>"></div>
        </div>
        <div class="form-group"><label>Social Links</label>
            <input type="text" name="social_facebook" placeholder="Facebook URL" value="<?= e($social['facebook'] ?? '') ?>">
            <input type="text" name="social_twitter" placeholder="Twitter URL" value="<?= e($social['twitter'] ?? '') ?>" style="margin-top:0.5rem">
            <input type="text" name="social_instagram" placeholder="Instagram URL" value="<?= e($social['instagram'] ?? '') ?>" style="margin-top:0.5rem">
        </div>
        <div class="form-group"><label>Image</label><div class="image-upload" data-field="image"><input type="hidden" name="image" value="<?= e($t['image'] ?? '') ?>"><?php if (!empty($t['image'])): ?><img src="<?= e($t['image']) ?>"><?php endif; ?><button type="button" class="btn btn-sm" onclick="uploadImage('image')">Upload</button></div></div>
        <div class="form-group"><label>Status</label><select name="active"><option value="1" <?= ($t['active'] ?? 1) ? 'selected' : '' ?>>Active</option><option value="0" <?= isset($t['active']) && !$t['active'] ? 'selected' : '' ?>>Hidden</option></select></div>
        <div class="form-actions"><button type="submit" class="btn btn-primary">Save</button><a href="admin.php?page=team" class="btn btn-outline">Cancel</a></div>
    </form>
</div>
<script>document.getElementById('item-form').onsubmit=async e=>{e.preventDefault();const f=new FormData(e.target);const d=Object.fromEntries(f);d.social_links={facebook:d.social_facebook,twitter:d.social_twitter,instagram:d.social_instagram};await fetch('admin.php?ajax=save-team',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(d)});location.href='admin.php?page=team';};</script>
