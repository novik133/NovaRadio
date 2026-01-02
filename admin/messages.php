<?php $messages = fetchAll("SELECT * FROM messages ORDER BY created_at DESC"); ?>
<div class="card">
    <div class="card-header"><h3>Contact Messages</h3></div>
    <table class="table">
        <thead><tr><th>Date</th><th>Name</th><th>Email</th><th>Message</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($messages as $msg): ?>
            <tr class="<?= $msg['is_read'] ? '' : 'unread' ?>">
                <td><?= date('M j, H:i', strtotime($msg['created_at'])) ?></td>
                <td><?= e($msg['name']) ?></td>
                <td><a href="mailto:<?= e($msg['email']) ?>"><?= e($msg['email']) ?></a></td>
                <td><?= e(substr($msg['message'], 0, 80)) ?>...</td>
                <td><span class="badge <?= $msg['is_read'] ? 'badge-muted' : 'badge-success' ?>"><?= $msg['is_read'] ? 'Read' : 'New' ?></span></td>
                <td>
                    <?php if (!$msg['is_read']): ?><button class="btn btn-sm" onclick="markRead(<?= $msg['id'] ?>)">Mark Read</button><?php endif; ?>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem('message', <?= $msg['id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
async function markRead(id) {
    await fetch('admin.php?ajax=mark-read', {method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({id})});
    location.reload();
}
</script>
