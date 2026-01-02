<?php $f = isset($_GET['id']) ? fetch("SELECT * FROM faq WHERE id = ?", [$_GET['id']]) : null; ?>
<div class="card">
    <div class="card-header"><h3><?= $f ? 'Edit' : 'Add' ?> FAQ</h3></div>
    <form id="item-form" class="form">
        <input type="hidden" name="id" value="<?= $f['id'] ?? '' ?>">
        <div class="form-group"><label>Question *</label><input type="text" name="question" value="<?= e($f['question'] ?? '') ?>" required></div>
        <div class="form-group"><label>Answer *</label><textarea name="answer" rows="5" required><?= e($f['answer'] ?? '') ?></textarea></div>
        <div class="row-3">
            <div class="form-group"><label>Category</label><input type="text" name="category" value="<?= e($f['category'] ?? '') ?>" placeholder="e.g., General, Technical"></div>
            <div class="form-group"><label>Sort Order</label><input type="number" name="sort_order" value="<?= $f['sort_order'] ?? 0 ?>"></div>
            <div class="form-group"><label>Status</label><select name="active"><option value="1" <?= ($f['active'] ?? 1) ? 'selected' : '' ?>>Active</option><option value="0" <?= isset($f['active']) && !$f['active'] ? 'selected' : '' ?>>Hidden</option></select></div>
        </div>
        <div class="form-actions"><button type="submit" class="btn btn-primary">Save</button><a href="admin.php?page=faq" class="btn btn-outline">Cancel</a></div>
    </form>
</div>
<script>document.getElementById('item-form').onsubmit=async e=>{e.preventDefault();const f=new FormData(e.target);const d=Object.fromEntries(f);await fetch('admin.php?ajax=save-faq',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(d)});location.href='admin.php?page=faq';};</script>
