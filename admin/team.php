<?php $items = fetchAll("SELECT * FROM team ORDER BY sort_order, name"); ?>
<div class="card">
    <div class="card-header"><h3>👥 Team Members</h3><a href="admin.php?page=team-edit" class="btn btn-primary">Add Member</a></div>
    <table class="table">
        <thead><tr><th>Image</th><th>Name</th><th>Role</th><th>Email</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($items as $t): ?>
            <tr>
                <td><?php if ($t['image']): ?><img src="<?= e($t['image']) ?>" class="thumb"><?php endif; ?></td>
                <td><strong><?= e($t['name']) ?></strong></td>
                <td><?= e($t['role']) ?></td>
                <td><?= e($t['email']) ?></td>
                <td><span class="badge <?= $t['active'] ? 'badge-success' : '' ?>"><?= $t['active'] ? 'Active' : 'Hidden' ?></span></td>
                <td>
                    <a href="admin.php?page=team-edit&id=<?= $t['id'] ?>" class="btn btn-sm">Edit</a>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem('team', <?= $t['id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
