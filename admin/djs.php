<?php $djs = fetchAll("SELECT * FROM djs ORDER BY name"); ?>
<div class="card">
    <div class="card-header">
        <h3>All DJs</h3>
        <a href="admin.php?page=dj-edit" class="btn btn-primary">Add DJ</a>
    </div>
    <table class="table">
        <thead><tr><th>Image</th><th>Name</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($djs as $dj): ?>
            <tr>
                <td><img src="<?= e($dj['image'] ?: 'assets/img/placeholder.png') ?>" class="thumb"></td>
                <td><?= e($dj['name']) ?></td>
                <td><span class="badge <?= $dj['active'] ? 'badge-success' : 'badge-muted' ?>"><?= $dj['active'] ? 'Active' : 'Inactive' ?></span></td>
                <td>
                    <a href="admin.php?page=dj-edit&id=<?= $dj['id'] ?>" class="btn btn-sm">Edit</a>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem('dj', <?= $dj['id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
