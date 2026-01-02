<?php
requireLogin();
$artists = fetchAll("SELECT * FROM artists ORDER BY name");
?>
<div class="admin-header">
    <h1>Artists</h1>
    <a href="admin.php?page=artist-edit" class="btn btn-primary">Add Artist</a>
</div>
<table class="table">
    <thead><tr><th>Name</th><th>Genre</th><th>Website</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($artists as $a): ?>
    <tr>
        <td><?= e($a['name']) ?></td>
        <td><?= e($a['genre']) ?></td>
        <td><?= $a['website'] ? '<a href="' . e($a['website']) . '" target="_blank">Visit</a>' : '-' ?></td>
        <td><span class="badge <?= $a['active'] ? 'badge-success' : 'badge-secondary' ?>"><?= $a['active'] ? 'Active' : 'Hidden' ?></span></td>
        <td>
            <a href="admin.php?page=artist-edit&id=<?= $a['id'] ?>" class="btn btn-sm">Edit</a>
            <button onclick="deleteItem('artist', <?= $a['id'] ?>)" class="btn btn-sm btn-danger">Delete</button>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
