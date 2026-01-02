<?php $r = isset($_GET['id']) ? fetch("SELECT * FROM redirects WHERE id = ?", [$_GET['id']]) : null; ?>
<div class="card">
    <div class="card-header"><h3><?= $r ? 'Edit' : 'Add' ?> Redirect</h3></div>
    <form id="item-form" class="form">
        <input type="hidden" name="id" value="<?= $r['id'] ?? '' ?>">
        <div class="form-group"><label>Source URL *</label><input type="text" name="source_url" value="<?= e($r['source_url'] ?? '') ?>" required placeholder="/old-page"></div>
        <div class="form-group"><label>Target URL *</label><input type="text" name="target_url" value="<?= e($r['target_url'] ?? '') ?>" required placeholder="/new-page or https://..."></div>
        <div class="row-2">
            <div class="form-group"><label>Redirect Type</label><select name="redirect_type"><option value="301" <?= ($r['redirect_type'] ?? '301') == '301' ? 'selected' : '' ?>>301 (Permanent)</option><option value="302" <?= ($r['redirect_type'] ?? '') == '302' ? 'selected' : '' ?>>302 (Temporary)</option></select></div>
            <div class="form-group"><label>Status</label><select name="active"><option value="1" <?= ($r['active'] ?? 1) ? 'selected' : '' ?>>Active</option><option value="0" <?= isset($r['active']) && !$r['active'] ? 'selected' : '' ?>>Disabled</option></select></div>
        </div>
        <div class="form-actions"><button type="submit" class="btn btn-primary">Save</button><a href="admin.php?page=redirects" class="btn btn-outline">Cancel</a></div>
    </form>
</div>
<script>document.getElementById('item-form').onsubmit=async e=>{e.preventDefault();const f=new FormData(e.target);const d=Object.fromEntries(f);await fetch('admin.php?ajax=save-redirect',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(d)});location.href='admin.php?page=redirects';};</script>
