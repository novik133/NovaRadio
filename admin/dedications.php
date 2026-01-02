<?php
requireLogin();
$dedications = fetchAll("SELECT d.*, s.name as station_name FROM dedications d LEFT JOIN stations s ON d.station_id = s.id ORDER BY d.created_at DESC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['status'])) {
        update('dedications', ['status' => $_POST['status']], 'id = ?', [(int)$_POST['id']]);
        redirect('admin.php?page=dedications');
    }
    if (isset($_POST['delete'])) {
        delete('dedications', 'id = ?', [(int)$_POST['id']]);
        redirect('admin.php?page=dedications');
    }
}
?>
<div class="admin-header"><h1>Dedications</h1></div>
<table class="table">
    <thead><tr><th>From → To</th><th>Message</th><th>Song</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($dedications as $d): ?>
    <tr>
        <td><strong><?= e($d['from_name']) ?></strong> → <?= e($d['to_name']) ?></td>
        <td><?= e(substr($d['message'], 0, 80)) ?></td>
        <td><?= $d['song_artist'] ? e($d['song_artist'] . ' - ' . $d['song_title']) : '-' ?></td>
        <td><span class="badge badge-<?= $d['status'] === 'played' ? 'success' : ($d['status'] === 'pending' ? 'warning' : 'secondary') ?>"><?= ucfirst($d['status']) ?></span></td>
        <td><?= date('M j', strtotime($d['created_at'])) ?></td>
        <td>
            <form method="post" style="display:inline">
                <input type="hidden" name="id" value="<?= $d['id'] ?>">
                <select name="status" onchange="this.form.submit()">
                    <option value="pending" <?= $d['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="approved" <?= $d['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="played" <?= $d['status'] === 'played' ? 'selected' : '' ?>>Played</option>
                    <option value="rejected" <?= $d['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
                <button name="delete" class="btn btn-sm btn-danger">×</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
