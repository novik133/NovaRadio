<?php
requireLogin();
$ads = fetchAll("SELECT * FROM ads ORDER BY created_at DESC");
?>
<div class="admin-header">
    <h1>Ads / Banners</h1>
    <a href="admin.php?page=ad-edit" class="btn btn-primary">Add Ad</a>
</div>
<table class="table">
    <thead><tr><th>Name</th><th>Position</th><th>Impressions</th><th>Clicks</th><th>CTR</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($ads as $a): ?>
    <tr>
        <td><?= e($a['name']) ?></td>
        <td><?= e($a['position']) ?></td>
        <td><?= $a['impressions'] ?></td>
        <td><?= $a['clicks'] ?></td>
        <td><?= $a['impressions'] ? round($a['clicks'] / $a['impressions'] * 100, 2) : 0 ?>%</td>
        <td><span class="badge <?= $a['active'] ? 'badge-success' : 'badge-secondary' ?>"><?= $a['active'] ? 'Active' : 'Inactive' ?></span></td>
        <td>
            <a href="admin.php?page=ad-edit&id=<?= $a['id'] ?>" class="btn btn-sm">Edit</a>
            <button onclick="deleteItem('ad', <?= $a['id'] ?>)" class="btn btn-sm btn-danger">Delete</button>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
