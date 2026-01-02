<?php
requireLogin();
$contestId = (int)($_GET['id'] ?? 0);
$contest = fetch("SELECT * FROM contests WHERE id = ?", [$contestId]);
$entries = fetchAll("SELECT * FROM contest_entries WHERE contest_id = ? ORDER BY created_at DESC", [$contestId]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['pick_winner'])) {
        $winnerId = (int)$_POST['winner_id'];
        update('contests', ['winner_id' => $winnerId], 'id = ?', [$contestId]);
        redirect("admin.php?page=contest-entries&id=$contestId&msg=winner");
    }
    if (isset($_POST['delete_entry'])) {
        delete('contest_entries', 'id = ?', [(int)$_POST['entry_id']]);
        redirect("admin.php?page=contest-entries&id=$contestId");
    }
    if (isset($_POST['export'])) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="contest_entries.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['Name', 'Email', 'Phone', 'Answer', 'Date']);
        foreach ($entries as $e) fputcsv($out, [$e['name'], $e['email'], $e['phone'], $e['answer'], $e['created_at']]);
        fclose($out);
        exit;
    }
}
?>
<div class="admin-header">
    <h1>Contest Entries<?= $contest ? ' - ' . e($contest['title']) : '' ?></h1>
    <div>
        <a href="admin.php?page=contests" class="btn">← Back</a>
        <form method="post" style="display:inline"><button name="export" class="btn btn-primary">Export CSV</button></form>
    </div>
</div>

<?php if (isset($_GET['msg'])): ?><div class="alert alert-success">Winner selected!</div><?php endif; ?>
<?php if ($contest['winner_id']): ?>
<?php $winner = fetch("SELECT * FROM contest_entries WHERE id = ?", [$contest['winner_id']]); ?>
<div class="alert alert-success">🏆 Winner: <strong><?= e($winner['name']) ?></strong> (<?= e($winner['email']) ?>)</div>
<?php endif; ?>

<table class="table">
    <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Answer</th><th>Date</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($entries as $e): ?>
    <tr class="<?= $contest['winner_id'] == $e['id'] ? 'winner-row' : '' ?>">
        <td><?= e($e['name']) ?> <?= $contest['winner_id'] == $e['id'] ? '🏆' : '' ?></td>
        <td><?= e($e['email']) ?></td>
        <td><?= e($e['phone'] ?: '-') ?></td>
        <td><?= e(substr($e['answer'], 0, 100)) ?></td>
        <td><?= date('M j, Y', strtotime($e['created_at'])) ?></td>
        <td>
            <form method="post" style="display:inline">
                <input type="hidden" name="winner_id" value="<?= $e['id'] ?>">
                <input type="hidden" name="entry_id" value="<?= $e['id'] ?>">
                <button name="pick_winner" class="btn btn-sm btn-success">Pick Winner</button>
                <button name="delete_entry" class="btn btn-sm btn-danger">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
