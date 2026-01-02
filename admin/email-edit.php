<?php $t = isset($_GET['id']) ? fetch("SELECT * FROM email_templates WHERE id = ?", [$_GET['id']]) : null; ?>
<div class="card">
    <div class="card-header"><h3><?= $t ? 'Edit' : 'Add' ?> Email Template</h3></div>
    <form id="item-form" class="form">
        <input type="hidden" name="id" value="<?= $t['id'] ?? '' ?>">
        <div class="row-2">
            <div class="form-group"><label>Name *</label><input type="text" name="name" value="<?= e($t['name'] ?? '') ?>" required></div>
            <div class="form-group"><label>Slug *</label><input type="text" name="slug" value="<?= e($t['slug'] ?? '') ?>" required placeholder="e.g., welcome-email"></div>
        </div>
        <div class="form-group"><label>Subject *</label><input type="text" name="subject" value="<?= e($t['subject'] ?? '') ?>" required></div>
        <div class="form-group"><label>Body *</label><textarea name="body" rows="12" required><?= e($t['body'] ?? '') ?></textarea><small>Use {{variable}} for dynamic content</small></div>
        <div class="form-group"><label>Status</label><select name="active"><option value="1" <?= ($t['active'] ?? 1) ? 'selected' : '' ?>>Active</option><option value="0" <?= isset($t['active']) && !$t['active'] ? 'selected' : '' ?>>Disabled</option></select></div>
        <div class="form-actions"><button type="submit" class="btn btn-primary">Save</button><a href="admin.php?page=emails" class="btn btn-outline">Cancel</a></div>
    </form>
</div>
<script>document.getElementById('item-form').onsubmit=async e=>{e.preventDefault();const f=new FormData(e.target);const d=Object.fromEntries(f);await fetch('admin.php?ajax=save-email-template',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(d)});location.href='admin.php?page=emails';};</script>
