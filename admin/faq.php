<?php $items = fetchAll("SELECT * FROM faq ORDER BY category, sort_order"); $categories = array_unique(array_column($items, 'category')); ?>
<div class="card">
    <div class="card-header"><h3>❓ FAQ</h3><a href="admin.php?page=faq-edit" class="btn btn-primary">Add Question</a></div>
    <table class="table">
        <thead><tr><th>Question</th><th>Category</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($items as $f): ?>
            <tr>
                <td><strong><?= e($f['question']) ?></strong></td>
                <td><span class="badge"><?= e($f['category'] ?: 'General') ?></span></td>
                <td><span class="badge <?= $f['active'] ? 'badge-success' : '' ?>"><?= $f['active'] ? 'Active' : 'Hidden' ?></span></td>
                <td>
                    <a href="admin.php?page=faq-edit&id=<?= $f['id'] ?>" class="btn btn-sm">Edit</a>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem('faq', <?= $f['id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
