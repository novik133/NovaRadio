<?php
requireLogin();
$id = $_GET['id'] ?? null;
$room = $id ? fetch("SELECT * FROM chat_rooms WHERE id = ?", [$id]) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $topic = trim($_POST['topic'] ?? '');
    $sortOrder = (int)($_POST['sort_order'] ?? 0);
    $active = isset($_POST['active']) ? 1 : 0;
    
    if (!$name || !$slug) {
        $error = 'Name and slug are required';
    } else {
        $existing = fetch("SELECT id FROM chat_rooms WHERE slug = ? AND id != ?", [$slug, $id ?? 0]);
        if ($existing) {
            $error = 'Slug already exists';
        } else {
            $data = ['name' => $name, 'slug' => $slug, 'topic' => $topic, 'sort_order' => $sortOrder, 'active' => $active];
            if ($id) {
                update('chat_rooms', $data, 'id = ?', [$id]);
            } else {
                insert('chat_rooms', $data);
            }
            redirect('admin.php?page=chat-rooms&msg=saved');
        }
    }
}
?>
<div class="admin-header">
    <h1><?= $room ? 'Edit' : 'Add' ?> Chat Room</h1>
    <a href="admin.php?page=chat-rooms" class="btn">← Back</a>
</div>

<?php if (isset($error)): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<form method="post" class="form-card">
    <div class="form-row">
        <div class="form-group flex-2">
            <label>Room Name</label>
            <input type="text" name="name" value="<?= e($room['name'] ?? $_POST['name'] ?? '') ?>" required onkeyup="generateSlug(this)">
        </div>
        <div class="form-group">
            <label>Slug</label>
            <input type="text" name="slug" id="slug" value="<?= e($room['slug'] ?? $_POST['slug'] ?? '') ?>" required>
        </div>
    </div>
    <div class="form-group">
        <label>Topic / Description</label>
        <input type="text" name="topic" value="<?= e($room['topic'] ?? $_POST['topic'] ?? '') ?>" placeholder="What is this room about?">
    </div>
    <div class="form-group">
        <label>Sort Order</label>
        <input type="number" name="sort_order" value="<?= $room['sort_order'] ?? $_POST['sort_order'] ?? 0 ?>" min="0">
    </div>
    <label class="checkbox">
        <input type="checkbox" name="active" value="1" <?= ($room['active'] ?? 1) ? 'checked' : '' ?>> Active
    </label>
    <button type="submit" class="btn btn-primary">Save Room</button>
</form>

<script>
function generateSlug(input) {
    document.getElementById('slug').value = input.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
}
</script>
