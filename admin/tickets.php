<?php $items = fetchAll("SELECT * FROM tickets ORDER BY FIELD(status,'open','in_progress','waiting','resolved','closed'), FIELD(priority,'urgent','high','medium','low'), created_at DESC LIMIT 100"); ?>
<div class="card">
    <div class="card-header"><h3>🎫 Support Tickets</h3></div>
    <table class="table">
        <thead><tr><th>Ticket #</th><th>Subject</th><th>From</th><th>Category</th><th>Priority</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($items as $t): ?>
            <tr>
                <td><strong><?= e($t['ticket_number']) ?></strong></td>
                <td><?= e($t['subject']) ?></td>
                <td><?= e($t['name']) ?><br><small><?= e($t['email']) ?></small></td>
                <td><span class="badge"><?= ucfirst($t['category']) ?></span></td>
                <td><span class="badge badge-priority-<?= $t['priority'] ?>"><?= ucfirst($t['priority']) ?></span></td>
                <td><span class="badge badge-status-<?= $t['status'] ?>"><?= ucfirst(str_replace('_', ' ', $t['status'])) ?></span></td>
                <td><?= date('M j', strtotime($t['created_at'])) ?></td>
                <td><a href="admin.php?page=ticket-view&id=<?= $t['id'] ?>" class="btn btn-sm">View</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
