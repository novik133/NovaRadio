<?php
requireLogin();
$polls = fetchAll("SELECT p.*, (SELECT SUM(votes) FROM poll_options WHERE poll_id = p.id) as total_votes FROM polls p ORDER BY p.created_at DESC");
?>
<div class="admin-header">
    <h1>Polls</h1>
    <a href="admin.php?page=poll-edit" class="btn btn-primary">Add Poll</a>
</div>
<table class="table">
    <thead><tr><th>Question</th><th>Votes</th><th>Ends</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($polls as $p): ?>
    <tr>
        <td><?= e($p['question']) ?></td>
        <td><?= $p['total_votes'] ?? 0 ?></td>
        <td><?= $p['ends_at'] ? date('M j, Y', strtotime($p['ends_at'])) : 'Never' ?></td>
        <td><span class="badge <?= $p['active'] ? 'badge-success' : 'badge-secondary' ?>"><?= $p['active'] ? 'Active' : 'Closed' ?></span></td>
        <td>
            <a href="admin.php?page=poll-edit&id=<?= $p['id'] ?>" class="btn btn-sm">Edit</a>
            <button onclick="deleteItem('poll', <?= $p['id'] ?>)" class="btn btn-sm btn-danger">Delete</button>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
