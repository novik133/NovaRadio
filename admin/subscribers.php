<?php
requireLogin();
$subscribers = fetchAll("SELECT * FROM subscribers ORDER BY created_at DESC");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    delete('subscribers', 'id = ?', [(int)$_POST['delete']]);
    redirect('admin.php?page=subscribers');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['export'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="subscribers.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Email', 'Name', 'Confirmed', 'Date']);
    foreach ($subscribers as $s) fputcsv($out, [$s['email'], $s['name'], $s['confirmed'] ? 'Yes' : 'No', $s['created_at']]);
    fclose($out);
    exit;
}
?>
<div class="admin-header">
    <h1>Newsletter Subscribers (<?= count($subscribers) ?>)</h1>
    <form method="post" style="display:inline"><button name="export" class="btn btn-primary">Export CSV</button></form>
</div>
<table class="table">
    <thead><tr><th>Email</th><th>Name</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($subscribers as $s): ?>
    <tr>
        <td><?= e($s['email']) ?></td>
        <td><?= e($s['name'] ?: '-') ?></td>
        <td><span class="badge <?= $s['confirmed'] ? 'badge-success' : 'badge-warning' ?>"><?= $s['confirmed'] ? 'Confirmed' : 'Pending' ?></span></td>
        <td><?= date('M j, Y', strtotime($s['created_at'])) ?></td>
        <td><form method="post" style="display:inline"><button name="delete" value="<?= $s['id'] ?>" class="btn btn-sm btn-danger">Delete</button></form></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
