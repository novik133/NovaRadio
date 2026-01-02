<?php $items = fetchAll("SELECT d.*, dj.name as dj_name FROM downloads d LEFT JOIN djs dj ON d.dj_id = dj.id ORDER BY d.created_at DESC"); ?>
<div class="card">
    <div class="card-header"><h3>📥 Downloads</h3><a href="admin.php?page=download-edit" class="btn btn-primary">Add Download</a></div>
    <table class="table">
        <thead><tr><th>Image</th><th>Title</th><th>Category</th><th>DJ</th><th>Downloads</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($items as $d): ?>
            <tr>
                <td><?php if ($d['image']): ?><img src="<?= e($d['image']) ?>" class="thumb"><?php endif; ?></td>
                <td><strong><?= e($d['title']) ?></strong></td>
                <td><span class="badge"><?= e($d['category'] ?: '-') ?></span></td>
                <td><?= e($d['dj_name'] ?: '-') ?></td>
                <td><?= number_format($d['download_count']) ?></td>
                <td><span class="badge <?= $d['active'] ? 'badge-success' : '' ?>"><?= $d['active'] ? 'Active' : 'Hidden' ?></span></td>
                <td>
                    <a href="admin.php?page=download-edit&id=<?= $d['id'] ?>" class="btn btn-sm">Edit</a>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem('download', <?= $d['id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
