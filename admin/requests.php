<?php $requests = fetchAll("SELECT * FROM requests ORDER BY created_at DESC"); ?>
<div class="card">
    <div class="card-header"><h3>Song Requests</h3></div>
    <table class="table">
        <thead><tr><th>Date</th><th>Listener</th><th>Artist</th><th>Song</th><th>Message</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($requests as $req): ?>
            <tr>
                <td><?= date('M j, H:i', strtotime($req['created_at'])) ?></td>
                <td><?= e($req['listener_name']) ?></td>
                <td><?= e($req['song_artist']) ?></td>
                <td><?= e($req['song_title']) ?></td>
                <td><?= e(substr($req['message'] ?? '', 0, 50)) ?></td>
                <td>
                    <select onchange="updateStatus(<?= $req['id'] ?>, this.value)" class="status-select">
                        <option value="pending" <?= $req['status']=='pending'?'selected':'' ?>>Pending</option>
                        <option value="approved" <?= $req['status']=='approved'?'selected':'' ?>>Approved</option>
                        <option value="played" <?= $req['status']=='played'?'selected':'' ?>>Played</option>
                        <option value="rejected" <?= $req['status']=='rejected'?'selected':'' ?>>Rejected</option>
                    </select>
                </td>
                <td><button class="btn btn-sm btn-danger" onclick="deleteItem('request', <?= $req['id'] ?>)">Delete</button></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
async function updateStatus(id, status) {
    await fetch('admin.php?ajax=update-request', {method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({id,status})});
}
</script>
