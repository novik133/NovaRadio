<?php
requireLogin();
$rooms = fetchAll("SELECT * FROM chat_rooms ORDER BY sort_order, name");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_room'])) {
        $roomId = (int)$_POST['room_id'];
        $room = fetch("SELECT is_default FROM chat_rooms WHERE id = ?", [$roomId]);
        if (!$room['is_default']) {
            delete('chat_rooms', 'id = ?', [$roomId]);
        }
        redirect('admin.php?page=chat-rooms&msg=deleted');
    }
    if (isset($_POST['set_default'])) {
        query("UPDATE chat_rooms SET is_default = 0");
        update('chat_rooms', ['is_default' => 1], 'id = ?', [(int)$_POST['room_id']]);
        redirect('admin.php?page=chat-rooms&msg=updated');
    }
}
$rooms = fetchAll("SELECT * FROM chat_rooms ORDER BY sort_order, name");
?>
<div class="admin-header">
    <h1>Chat Rooms</h1>
    <div>
        <a href="admin.php?page=chat" class="btn">← Back to Chat</a>
        <a href="admin.php?page=chat-room-edit" class="btn btn-primary">Add Room</a>
    </div>
</div>

<?php if (isset($_GET['msg'])): ?><div class="alert alert-success">Updated successfully</div><?php endif; ?>

<table class="table">
    <thead><tr><th>Name</th><th>Slug</th><th>Topic</th><th>Order</th><th>Default</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rooms as $r): ?>
    <tr>
        <td><?= e($r['name']) ?></td>
        <td><?= e($r['slug']) ?></td>
        <td><?= e($r['topic'] ?: '-') ?></td>
        <td><?= $r['sort_order'] ?></td>
        <td><?= $r['is_default'] ? '<span class="badge badge-primary">Default</span>' : '' ?></td>
        <td><span class="badge <?= $r['active'] ? 'badge-success' : 'badge-secondary' ?>"><?= $r['active'] ? 'Active' : 'Inactive' ?></span></td>
        <td>
            <a href="admin.php?page=chat-room-edit&id=<?= $r['id'] ?>" class="btn btn-sm">Edit</a>
            <form method="post" style="display:inline">
                <input type="hidden" name="room_id" value="<?= $r['id'] ?>">
                <?php if (!$r['is_default']): ?>
                <button type="submit" name="set_default" class="btn btn-sm">Set Default</button>
                <button type="submit" name="delete_room" class="btn btn-sm btn-danger" onclick="return confirm('Delete room and all messages?')">Delete</button>
                <?php endif; ?>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
