<?php
requireLogin();
$galleryId = $_GET['gallery'] ?? null;
$gallery = $galleryId ? fetch("SELECT * FROM galleries WHERE id = ?", [$galleryId]) : null;
$images = $galleryId ? fetchAll("SELECT * FROM gallery_images WHERE gallery_id = ? ORDER BY sort_order", [$galleryId]) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    delete('gallery_images', 'id = ?', [(int)$_POST['delete']]);
    redirect("admin.php?page=gallery-images&gallery=$galleryId");
}
?>
<div class="admin-header">
    <h1>Gallery Images<?= $gallery ? ' - ' . e($gallery['title']) : '' ?></h1>
</div>
<?php if (!$gallery): ?>
<p>Select a gallery first: <a href="admin.php?page=galleries">View Galleries</a></p>
<?php else: ?>
<div class="form-card">
    <h3>Upload Images</h3>
    <input type="file" id="gallery-upload" accept="image/*" multiple>
    <div id="upload-progress"></div>
</div>
<div class="gallery-admin-grid">
    <?php foreach ($images as $img): ?>
    <div class="gallery-admin-item">
        <img src="<?= e($img['image']) ?>">
        <input type="text" value="<?= e($img['caption']) ?>" placeholder="Caption" onchange="updateCaption(<?= $img['id'] ?>, this.value)">
        <form method="post" style="display:inline"><button name="delete" value="<?= $img['id'] ?>" class="btn btn-sm btn-danger">Delete</button></form>
    </div>
    <?php endforeach; ?>
</div>
<script>
document.getElementById('gallery-upload').onchange = async function() {
    const progress = document.getElementById('upload-progress');
    for (const file of this.files) {
        const fd = new FormData();
        fd.append('image', file);
        progress.textContent = 'Uploading ' + file.name + '...';
        const res = await fetch('admin.php?ajax=upload', { method: 'POST', body: fd });
        const json = await res.json();
        if (json.success) {
            await fetch('admin.php?ajax=save-gallery-image', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ gallery_id: <?= $galleryId ?>, image: json.url })
            });
        }
    }
    location.reload();
};
async function updateCaption(id, caption) {
    await fetch('admin.php?ajax=update-gallery-image', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ id, caption })
    });
}
</script>
<?php endif; ?>
