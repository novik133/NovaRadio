<?php
requireLogin();
$comments = fetchAll("SELECT c.*, p.title as post_title FROM comments c LEFT JOIN posts p ON c.post_id = p.id ORDER BY c.created_at DESC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve'])) { update('comments', ['approved' => 1], 'id = ?', [(int)$_POST['id']]); redirect('admin.php?page=comments'); }
    if (isset($_POST['delete'])) { delete('comments', 'id = ?', [(int)$_POST['id']]); redirect('admin.php?page=comments'); }
}
?>
<div class="admin-header">
    <h1>Comments</h1>
</div>
<table class="table">
    <thead><tr><th>Author</th><th>Comment</th><th>Post</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($comments as $c): ?>
    <tr>
        <td><?= e($c['author_name']) ?><br><small><?= e($c['author_email']) ?></small></td>
        <td><?= e(substr($c['content'], 0, 100)) ?>...</td>
        <td><?= e($c['post_title']) ?></td>
        <td><span class="badge <?= $c['approved'] ? 'badge-success' : 'badge-warning' ?>"><?= $c['approved'] ? 'Approved' : 'Pending' ?></span></td>
        <td><?= date('M j, Y', strtotime($c['created_at'])) ?></td>
        <td>
            <form method="post" style="display:inline">
                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                <?php if (!$c['approved']): ?><button name="approve" class="btn btn-sm btn-success">Approve</button><?php endif; ?>
                <button name="delete" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
