<?php $items = fetchAll("SELECT * FROM email_templates ORDER BY name"); ?>
<div class="card">
    <div class="card-header"><h3>📧 Email Templates</h3><a href="admin.php?page=email-edit" class="btn btn-primary">Add Template</a></div>
    <table class="table">
        <thead><tr><th>Name</th><th>Slug</th><th>Subject</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($items as $t): ?>
            <tr>
                <td><strong><?= e($t['name']) ?></strong></td>
                <td><code><?= e($t['slug']) ?></code></td>
                <td><?= e($t['subject']) ?></td>
                <td><span class="badge <?= $t['active'] ? 'badge-success' : '' ?>"><?= $t['active'] ? 'Active' : 'Disabled' ?></span></td>
                <td>
                    <a href="admin.php?page=email-edit&id=<?= $t['id'] ?>" class="btn btn-sm">Edit</a>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem('email_template', <?= $t['id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
