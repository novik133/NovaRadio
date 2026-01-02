<?php
requireLogin();
$id = $_GET['id'] ?? null;
$post = $id ? fetch("SELECT * FROM posts WHERE id = ?", [$id]) : null;
?>
<div class="admin-header">
    <h1><?= $post ? 'Edit' : 'Add' ?> Post</h1>
    <a href="admin.php?page=posts" class="btn">← Back</a>
</div>
<form id="post-form" class="form-card">
    <input type="hidden" name="id" value="<?= $post['id'] ?? '' ?>">
    <div class="form-row">
        <div class="form-group flex-2">
            <label>Title</label>
            <input type="text" name="title" value="<?= e($post['title'] ?? '') ?>" required onkeyup="generateSlug(this, 'slug')">
        </div>
        <div class="form-group">
            <label>Slug</label>
            <input type="text" name="slug" id="slug" value="<?= e($post['slug'] ?? '') ?>" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Category</label>
            <input type="text" name="category" value="<?= e($post['category'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Tags (comma separated)</label>
            <input type="text" name="tags" value="<?= e($post['tags'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Publish Date</label>
            <input type="datetime-local" name="published_at" value="<?= $post['published_at'] ? date('Y-m-d\TH:i', strtotime($post['published_at'])) : '' ?>">
        </div>
    </div>
    <div class="form-group">
        <label>Excerpt</label>
        <textarea name="excerpt" rows="2"><?= e($post['excerpt'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
        <label>Content</label>
        <textarea name="content" rows="15" class="editor"><?= e($post['content'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
        <label>Featured Image</label>
        <div class="image-upload">
            <input type="text" name="image" id="image" value="<?= e($post['image'] ?? '') ?>">
            <input type="file" id="image-file" accept="image/*" onchange="uploadImage(this, 'image')">
            <label for="image-file" class="btn">Upload</label>
        </div>
        <?php if ($post['image'] ?? ''): ?><img src="<?= e($post['image']) ?>" class="preview-img"><?php endif; ?>
    </div>
    <div class="form-row">
        <label class="checkbox"><input type="checkbox" name="featured" value="1" <?= ($post['featured'] ?? 0) ? 'checked' : '' ?>> Featured</label>
        <label class="checkbox"><input type="checkbox" name="active" value="1" <?= ($post['active'] ?? 1) ? 'checked' : '' ?>> Published</label>
    </div>
    <button type="submit" class="btn btn-primary">Save Post</button>
</form>
<script>
document.getElementById('post-form').onsubmit = async e => {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(e.target));
    data.featured = data.featured ? 1 : 0;
    data.active = data.active ? 1 : 0;
    data.author_id = <?= $_SESSION['admin_id'] ?>;
    const res = await fetch('admin.php?ajax=save-post', { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(data) });
    const json = await res.json();
    if (json.success) location.href = 'admin.php?page=posts';
};
function generateSlug(input, target) {
    document.getElementById(target).value = input.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
}
</script>
