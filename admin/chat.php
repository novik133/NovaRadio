<?php
requireLogin();
$chatEnabled = getSetting('chat_enabled', '1');
$rooms = fetchAll("SELECT * FROM chat_rooms ORDER BY sort_order, name");
$chatUsers = fetchAll("SELECT * FROM chat_users ORDER BY last_seen DESC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['toggle_chat'])) {
        setSetting('chat_enabled', $chatEnabled === '1' ? '0' : '1', 'chat');
        redirect('admin.php?page=chat&msg=updated');
    }
    if (isset($_POST['toggle_op'])) {
        $userId = (int)$_POST['user_id'];
        $current = fetch("SELECT is_op FROM chat_users WHERE id = ?", [$userId]);
        update('chat_users', ['is_op' => $current['is_op'] ? 0 : 1], 'id = ?', [$userId]);
        redirect('admin.php?page=chat&msg=updated');
    }
    if (isset($_POST['toggle_ban'])) {
        $userId = (int)$_POST['user_id'];
        $current = fetch("SELECT is_banned FROM chat_users WHERE id = ?", [$userId]);
        update('chat_users', ['is_banned' => $current['is_banned'] ? 0 : 1], 'id = ?', [$userId]);
        redirect('admin.php?page=chat&msg=updated');
    }
    if (isset($_POST['delete_user'])) {
        delete('chat_users', 'id = ?', [(int)$_POST['user_id']]);
        redirect('admin.php?page=chat&msg=deleted');
    }
}
$chatEnabled = getSetting('chat_enabled', '1');
?>
<div class="admin-header">
    <h1>Chat Management</h1>
    <div>
        <a href="admin.php?page=chat-rooms" class="btn btn-primary">Manage Rooms</a>
        <form method="post" style="display:inline">
            <button type="submit" name="toggle_chat" class="btn <?= $chatEnabled === '1' ? 'btn-danger' : 'btn-success' ?>">
                <?= $chatEnabled === '1' ? 'Disable Chat' : 'Enable Chat' ?>
            </button>
        </form>
    </div>
</div>

<?php if (isset($_GET['msg'])): ?><div class="alert alert-success">Updated successfully</div><?php endif; ?>

<div class="card">
    <div class="card-header">Chat Rooms (<?= count($rooms) ?>)</div>
    <table class="table">
        <thead><tr><th>Name</th><th>Topic</th><th>Default</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach ($rooms as $r): ?>
        <tr>
            <td><?= e($r['name']) ?></td>
            <td><?= e($r['topic'] ?: '-') ?></td>
            <td><?= $r['is_default'] ? '✓' : '' ?></td>
            <td><span class="badge <?= $r['active'] ? 'badge-success' : 'badge-secondary' ?>"><?= $r['active'] ? 'Active' : 'Inactive' ?></span></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="card" style="margin-top:1rem">
    <div class="card-header">Chat Users (<?= count($chatUsers) ?>)</div>
    <table class="table">
        <thead><tr><th>Username</th><th>Email</th><th>Type</th><th>Status</th><th>Last Seen</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($chatUsers as $u): ?>
        <tr>
            <td><?= e($u['username']) ?> <?= $u['is_op'] ? '<span class="badge badge-warning">OP</span>' : '' ?></td>
            <td><?= e($u['email'] ?: '-') ?></td>
            <td><?= $u['is_guest'] ? 'Guest' : 'Registered' ?></td>
            <td><?= $u['is_banned'] ? '<span class="badge badge-danger">Banned</span>' : '<span class="badge badge-success">Active</span>' ?></td>
            <td><?= $u['last_seen'] ?></td>
            <td>
                <form method="post" style="display:inline">
                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                    <button type="submit" name="toggle_op" class="btn btn-sm"><?= $u['is_op'] ? 'Remove OP' : 'Give OP' ?></button>
                    <button type="submit" name="toggle_ban" class="btn btn-sm <?= $u['is_banned'] ? 'btn-success' : 'btn-warning' ?>"><?= $u['is_banned'] ? 'Unban' : 'Ban' ?></button>
                    <button type="submit" name="delete_user" class="btn btn-sm btn-danger" onclick="return confirm('Delete user?')">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
