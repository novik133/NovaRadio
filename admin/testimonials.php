<?php $items = fetchAll("SELECT * FROM testimonials ORDER BY sort_order, created_at DESC"); ?>
<div class="card">
    <div class="card-header"><h3>⭐ Testimonials</h3><a href="admin.php?page=testimonial-edit" class="btn btn-primary">Add Testimonial</a></div>
    <table class="table">
        <thead><tr><th>Image</th><th>Name</th><th>Role</th><th>Rating</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($items as $t): ?>
            <tr>
                <td><?php if ($t['image']): ?><img src="<?= e($t['image']) ?>" class="thumb"><?php endif; ?></td>
                <td><strong><?= e($t['name']) ?></strong></td>
                <td><?= e($t['role']) ?></td>
                <td><?= str_repeat('⭐', $t['rating']) ?></td>
                <td><span class="badge <?= $t['active'] ? 'badge-success' : '' ?>"><?= $t['active'] ? 'Active' : 'Hidden' ?></span></td>
                <td>
                    <a href="admin.php?page=testimonial-edit&id=<?= $t['id'] ?>" class="btn btn-sm">Edit</a>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem('testimonial', <?= $t['id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
