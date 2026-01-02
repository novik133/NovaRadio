<?php
requireLogin();
$podcasts = fetchAll("SELECT p.*, (SELECT COUNT(*) FROM episodes WHERE podcast_id = p.id) as episodes FROM podcasts p ORDER BY p.created_at DESC");
?>
<div class="admin-header">
    <h1>Podcasts</h1>
    <a href="admin.php?page=podcast-edit" class="btn btn-primary">Add Podcast</a>
</div>
<table class="table">
    <thead><tr><th>Title</th><th>Author</th><th>Category</th><th>Episodes</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($podcasts as $p): ?>
    <tr>
        <td><?= e($p['title']) ?></td>
        <td><?= e($p['author']) ?></td>
        <td><?= e($p['category']) ?></td>
        <td><a href="admin.php?page=episodes&podcast=<?= $p['id'] ?>"><?= $p['episodes'] ?> episodes</a></td>
        <td><span class="badge <?= $p['active'] ? 'badge-success' : 'badge-secondary' ?>"><?= $p['active'] ? 'Active' : 'Inactive' ?></span></td>
        <td>
            <a href="admin.php?page=podcast-edit&id=<?= $p['id'] ?>" class="btn btn-sm">Edit</a>
            <a href="admin.php?page=episodes&podcast=<?= $p['id'] ?>" class="btn btn-sm">Episodes</a>
            <button onclick="deleteItem('podcast', <?= $p['id'] ?>)" class="btn btn-sm btn-danger">Delete</button>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
