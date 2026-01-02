<?php
requireLogin();
$specials = fetchAll("SELECT sb.*, s.name as station_name, d.name as dj_name FROM special_broadcasts sb LEFT JOIN stations s ON sb.station_id = s.id LEFT JOIN djs d ON sb.dj_id = d.id ORDER BY sb.start_time DESC");
?>
<div class="admin-header">
    <h1>Special Broadcasts</h1>
    <a href="admin.php?page=special-edit" class="btn btn-primary">Add Broadcast</a>
</div>
<table class="table">
    <thead><tr><th>Title</th><th>Station</th><th>DJ</th><th>Date/Time</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($specials as $sb): ?>
    <tr>
        <td><?= e($sb['title']) ?></td>
        <td><?= e($sb['station_name'] ?: '-') ?></td>
        <td><?= e($sb['dj_name'] ?: '-') ?></td>
        <td><?= date('M j, g:i A', strtotime($sb['start_time'])) ?> - <?= date('g:i A', strtotime($sb['end_time'])) ?></td>
        <td>
            <?php if ($sb['is_live']): ?><span class="badge badge-danger">LIVE</span>
            <?php elseif (strtotime($sb['end_time']) < time()): ?><span class="badge badge-secondary">Ended</span>
            <?php else: ?><span class="badge badge-success">Scheduled</span><?php endif; ?>
        </td>
        <td>
            <a href="admin.php?page=special-edit&id=<?= $sb['id'] ?>" class="btn btn-sm">Edit</a>
            <button onclick="deleteItem('special', <?= $sb['id'] ?>)" class="btn btn-sm btn-danger">Delete</button>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
