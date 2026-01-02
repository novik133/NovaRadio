<?php
requireLogin();
$contests = fetchAll("SELECT c.*, (SELECT COUNT(*) FROM contest_entries WHERE contest_id = c.id) as entries FROM contests c ORDER BY c.created_at DESC");
?>
<div class="admin-header">
    <h1>Contests</h1>
    <a href="admin.php?page=contest-edit" class="btn btn-primary">Add Contest</a>
</div>
<table class="table">
    <thead><tr><th>Title</th><th>Prize</th><th>Entries</th><th>Ends</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($contests as $c): ?>
    <tr>
        <td><?= e($c['title']) ?></td>
        <td><?= e($c['prize']) ?></td>
        <td><a href="admin.php?page=contest-entries&id=<?= $c['id'] ?>"><?= $c['entries'] ?> entries</a></td>
        <td><?= $c['end_date'] ? date('M j, Y', strtotime($c['end_date'])) : '-' ?></td>
        <td><span class="badge <?= $c['active'] ? 'badge-success' : 'badge-secondary' ?>"><?= $c['active'] ? 'Active' : 'Closed' ?></span></td>
        <td>
            <a href="admin.php?page=contest-edit&id=<?= $c['id'] ?>" class="btn btn-sm">Edit</a>
            <a href="admin.php?page=contest-entries&id=<?= $c['id'] ?>" class="btn btn-sm">Entries</a>
            <button onclick="deleteItem('contest', <?= $c['id'] ?>)" class="btn btn-sm btn-danger">Delete</button>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
