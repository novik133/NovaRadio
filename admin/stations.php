<?php $stations = fetchAll("SELECT * FROM stations ORDER BY sort_order, name"); ?>
<div class="card">
    <div class="card-header"><h3>Radio Stations</h3><a href="admin.php?page=station-edit" class="btn btn-primary">Add Station</a></div>
    <table class="table">
        <thead><tr><th>Image</th><th>Name</th><th>Genre</th><th>Stream URL</th><th>Default</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($stations as $st): ?>
            <tr>
                <td><img src="<?= e($st['image'] ?: 'assets/img/placeholder.png') ?>" class="thumb"></td>
                <td><strong><?= e($st['name']) ?></strong><br><small class="text-muted"><?= e($st['slug']) ?></small></td>
                <td><?= e($st['genre']) ?></td>
                <td><small><?= e(substr($st['stream_url'], 0, 40)) ?>...</small></td>
                <td><?= $st['is_default'] ? '⭐' : '' ?></td>
                <td><span class="badge <?= $st['active'] ? 'badge-success' : 'badge-muted' ?>"><?= $st['active'] ? 'Active' : 'Inactive' ?></span></td>
                <td>
                    <a href="admin.php?page=station-edit&id=<?= $st['id'] ?>" class="btn btn-sm">Edit</a>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem('station', <?= $st['id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
