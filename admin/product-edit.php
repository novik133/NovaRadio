<?php $p = isset($_GET['id']) ? fetch("SELECT * FROM products WHERE id = ?", [$_GET['id']]) : null; ?>
<div class="card">
    <div class="card-header"><h3><?= $p ? 'Edit' : 'Add' ?> Product</h3></div>
    <form id="item-form" class="form">
        <input type="hidden" name="id" value="<?= $p['id'] ?? '' ?>">
        <div class="row-2">
            <div class="form-group"><label>Name *</label><input type="text" name="name" value="<?= e($p['name'] ?? '') ?>" required></div>
            <div class="form-group"><label>SKU</label><input type="text" name="sku" value="<?= e($p['sku'] ?? '') ?>"></div>
        </div>
        <div class="form-group"><label>Description</label><textarea name="description" rows="4"><?= e($p['description'] ?? '') ?></textarea></div>
        <div class="row-4">
            <div class="form-group"><label>Price *</label><input type="number" step="0.01" name="price" value="<?= $p['price'] ?? '' ?>" required></div>
            <div class="form-group"><label>Sale Price</label><input type="number" step="0.01" name="sale_price" value="<?= $p['sale_price'] ?? '' ?>"></div>
            <div class="form-group"><label>Stock</label><input type="number" name="stock" value="<?= $p['stock'] ?? 0 ?>"></div>
            <div class="form-group"><label>Category</label><input type="text" name="category" value="<?= e($p['category'] ?? '') ?>"></div>
        </div>
        <div class="form-group"><label>Image</label><div class="image-upload" data-field="image"><input type="hidden" name="image" value="<?= e($p['image'] ?? '') ?>"><?php if (!empty($p['image'])): ?><img src="<?= e($p['image']) ?>"><?php endif; ?><button type="button" class="btn btn-sm" onclick="uploadImage('image')">Upload</button></div></div>
        <div class="row-3">
            <div class="form-group"><label>Status</label><select name="active"><option value="1" <?= ($p['active'] ?? 1) ? 'selected' : '' ?>>Active</option><option value="0" <?= isset($p['active']) && !$p['active'] ? 'selected' : '' ?>>Hidden</option></select></div>
            <div class="form-group"><label>Featured</label><select name="featured"><option value="0" <?= empty($p['featured']) ? 'selected' : '' ?>>No</option><option value="1" <?= !empty($p['featured']) ? 'selected' : '' ?>>Yes</option></select></div>
        </div>
        <div class="form-actions"><button type="submit" class="btn btn-primary">Save</button><a href="admin.php?page=products" class="btn btn-outline">Cancel</a></div>
    </form>
</div>
<script>document.getElementById('item-form').onsubmit=async e=>{e.preventDefault();const f=new FormData(e.target);const d=Object.fromEntries(f);d.slug=d.name.toLowerCase().replace(/[^a-z0-9]+/g,'-');await fetch('admin.php?ajax=save-product',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(d)});location.href='admin.php?page=products';};</script>
