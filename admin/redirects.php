<?php $items = fetchAll("SELECT * FROM redirects ORDER BY created_at DESC"); ?>
<div class="card">
    <div class="card-header"><h3>🔀 Redirects</h3><a href="admin.php?page=redirect-edit" class="btn btn-primary">Add Redirect</a></div>
    <table class="table">
        <thead><tr><th>Source</th><th>Target</th><th>Type</th><th>Hits</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($items as $r): ?>
            <tr>
                <td><code><?= e($r['source_url']) ?></code></td>
                <td><code><?= e($r['target_url']) ?></code></td>
                <td><span class="badge"><?= $r['redirect_type'] ?></span></td>
                <td><?= number_format($r['hits']) ?></td>
                <td><span class="badge <?= $r['active'] ? 'badge-success' : '' ?>"><?= $r['active'] ? 'Active' : 'Disabled' ?></span></td>
                <td>
                    <a href="admin.php?page=redirect-edit&id=<?= $r['id'] ?>" class="btn btn-sm">Edit</a>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem('redirect', <?= $r['id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
