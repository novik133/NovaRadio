<?php $items = fetchAll("SELECT l.*, a.username FROM activity_log l LEFT JOIN admins a ON l.admin_id = a.id ORDER BY l.created_at DESC LIMIT 200"); ?>
<div class="card">
    <div class="card-header"><h3>📋 Activity Log</h3></div>
    <table class="table">
        <thead><tr><th>Time</th><th>User</th><th>Action</th><th>Entity</th><th>IP</th></tr></thead>
        <tbody>
            <?php foreach ($items as $l): ?>
            <tr>
                <td><?= date('M j, g:i A', strtotime($l['created_at'])) ?></td>
                <td><?= e($l['username'] ?: 'System') ?></td>
                <td><span class="badge"><?= e($l['action']) ?></span></td>
                <td><?= $l['entity_type'] ? e($l['entity_type']) . ' #' . $l['entity_id'] : '-' ?></td>
                <td><code><?= e($l['ip_address']) ?></code></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
