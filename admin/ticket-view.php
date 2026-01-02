<?php 
$t = fetch("SELECT * FROM tickets WHERE id = ?", [$_GET['id'] ?? 0]);
if (!$t) { echo '<div class="alert alert-danger">Ticket not found</div>'; return; }
$replies = fetchAll("SELECT r.*, a.username as admin_name FROM ticket_replies r LEFT JOIN admins a ON r.admin_id = a.id WHERE r.ticket_id = ? ORDER BY r.created_at", [$t['id']]);
?>
<div class="card">
    <div class="card-header">
        <h3>Ticket #<?= e($t['ticket_number']) ?></h3>
        <div>
            <select id="ticket-status" onchange="updateTicket()">
                <?php foreach (['open','in_progress','waiting','resolved','closed'] as $s): ?>
                <option value="<?= $s ?>" <?= $t['status'] == $s ? 'selected' : '' ?>><?= ucfirst(str_replace('_', ' ', $s)) ?></option>
                <?php endforeach; ?>
            </select>
            <select id="ticket-priority" onchange="updateTicket()">
                <?php foreach (['low','medium','high','urgent'] as $p): ?>
                <option value="<?= $p ?>" <?= $t['priority'] == $p ? 'selected' : '' ?>><?= ucfirst($p) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="card-body">
        <div class="ticket-meta">
            <p><strong>From:</strong> <?= e($t['name']) ?> (<?= e($t['email']) ?>)</p>
            <p><strong>Category:</strong> <?= ucfirst($t['category']) ?> | <strong>Created:</strong> <?= date('F j, Y g:i A', strtotime($t['created_at'])) ?></p>
        </div>
        <h4><?= e($t['subject']) ?></h4>
        <div class="ticket-thread">
            <?php foreach ($replies as $r): ?>
            <div class="ticket-reply <?= $r['is_staff'] ? 'staff' : 'user' ?>">
                <div class="reply-header">
                    <strong><?= $r['is_staff'] ? e($r['admin_name'] ?? 'Staff') : e($t['name']) ?></strong>
                    <span><?= date('M j, g:i A', strtotime($r['created_at'])) ?></span>
                </div>
                <div class="reply-body"><?= nl2br(e($r['message'])) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <form id="reply-form" class="reply-form">
            <textarea name="message" rows="4" placeholder="Type your reply..." required></textarea>
            <button type="submit" class="btn btn-primary">Send Reply</button>
        </form>
    </div>
</div>
<a href="admin.php?page=tickets" class="btn btn-outline">← Back to Tickets</a>
<script>
async function updateTicket() {
    await fetch('admin.php?ajax=update-ticket', {
        method: 'POST', headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id: <?= $t['id'] ?>, status: document.getElementById('ticket-status').value, priority: document.getElementById('ticket-priority').value})
    });
}
document.getElementById('reply-form').onsubmit = async e => {
    e.preventDefault();
    await fetch('admin.php?ajax=ticket-reply', {
        method: 'POST', headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ticket_id: <?= $t['id'] ?>, message: e.target.message.value})
    });
    location.reload();
};
</script>
