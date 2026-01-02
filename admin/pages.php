<?php $pages = fetchAll("SELECT * FROM pages ORDER BY title"); ?>
<div class="card">
    <div class="card-header"><h3>All Pages</h3><a href="admin.php?page=page-edit" class="btn btn-primary">Add Page</a></div>
    <table class="table">
        <thead><tr><th>Title</th><th>Slug</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($pages as $p): ?>
            <tr>
                <td><?= e($p['title']) ?></td>
                <td>/page.php?slug=<?= e($p['slug']) ?></td>
                <td><span class="badge <?= $p['active'] ? 'badge-success' : 'badge-muted' ?>"><?= $p['active'] ? 'Active' : 'Inactive' ?></span></td>
                <td>
                    <a href="admin.php?page=page-edit&id=<?= $p['id'] ?>" class="btn btn-sm">Edit</a>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem('page', <?= $p['id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
