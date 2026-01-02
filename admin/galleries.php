<?php
requireLogin();
$galleries = fetchAll("SELECT g.*, (SELECT COUNT(*) FROM gallery_images WHERE gallery_id = g.id) as images FROM galleries g ORDER BY g.created_at DESC");
?>
<div class="admin-header">
    <h1>Galleries</h1>
    <a href="admin.php?page=gallery-edit" class="btn btn-primary">Add Gallery</a>
</div>
<table class="table">
    <thead><tr><th>Title</th><th>Images</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($galleries as $g): ?>
    <tr>
        <td><?= e($g['title']) ?></td>
        <td><a href="admin.php?page=gallery-images&gallery=<?= $g['id'] ?>"><?= $g['images'] ?> images</a></td>
        <td><span class="badge <?= $g['active'] ? 'badge-success' : 'badge-secondary' ?>"><?= $g['active'] ? 'Active' : 'Hidden' ?></span></td>
        <td>
            <a href="admin.php?page=gallery-edit&id=<?= $g['id'] ?>" class="btn btn-sm">Edit</a>
            <a href="admin.php?page=gallery-images&gallery=<?= $g['id'] ?>" class="btn btn-sm">Images</a>
            <button onclick="deleteItem('gallery', <?= $g['id'] ?>)" class="btn btn-sm btn-danger">Delete</button>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
