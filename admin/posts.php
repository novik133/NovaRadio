<?php
requireLogin();
$posts = fetchAll("SELECT p.*, a.username as author FROM posts p LEFT JOIN admins a ON p.author_id = a.id ORDER BY p.created_at DESC");
?>
<div class="admin-header">
    <h1>Blog Posts</h1>
    <a href="admin.php?page=post-edit" class="btn btn-primary">Add Post</a>
</div>
<table class="table">
    <thead><tr><th>Title</th><th>Category</th><th>Author</th><th>Views</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($posts as $p): ?>
    <tr>
        <td><?= e($p['title']) ?> <?= $p['featured'] ? '⭐' : '' ?></td>
        <td><?= e($p['category']) ?></td>
        <td><?= e($p['author'] ?? '-') ?></td>
        <td><?= $p['views'] ?></td>
        <td><span class="badge <?= $p['active'] ? 'badge-success' : 'badge-secondary' ?>"><?= $p['active'] ? 'Published' : 'Draft' ?></span></td>
        <td>
            <a href="admin.php?page=post-edit&id=<?= $p['id'] ?>" class="btn btn-sm">Edit</a>
            <button onclick="deleteItem('post', <?= $p['id'] ?>)" class="btn btn-sm btn-danger">Delete</button>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
