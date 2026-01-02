<?php $items = fetchAll("SELECT * FROM products ORDER BY created_at DESC"); ?>
<div class="card">
    <div class="card-header"><h3>🛒 Products</h3><a href="admin.php?page=product-edit" class="btn btn-primary">Add Product</a></div>
    <table class="table">
        <thead><tr><th>Image</th><th>Name</th><th>Price</th><th>Stock</th><th>Category</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($items as $p): ?>
            <tr>
                <td><?php if ($p['image']): ?><img src="<?= e($p['image']) ?>" class="thumb"><?php endif; ?></td>
                <td><strong><?= e($p['name']) ?></strong><?= $p['featured'] ? ' ⭐' : '' ?></td>
                <td><?= $p['sale_price'] ? '<s>$'.number_format($p['price'],2).'</s> <strong>$'.number_format($p['sale_price'],2).'</strong>' : '$'.number_format($p['price'],2) ?></td>
                <td><?= $p['stock'] > 0 ? $p['stock'] : '<span class="text-danger">Out</span>' ?></td>
                <td><span class="badge"><?= e($p['category'] ?: '-') ?></span></td>
                <td><span class="badge <?= $p['active'] ? 'badge-success' : '' ?>"><?= $p['active'] ? 'Active' : 'Hidden' ?></span></td>
                <td>
                    <a href="admin.php?page=product-edit&id=<?= $p['id'] ?>" class="btn btn-sm">Edit</a>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem('product', <?= $p['id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
