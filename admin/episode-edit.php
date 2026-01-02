<?php
requireLogin();
$podcastId = $_GET['podcast'] ?? null;
$id = $_GET['id'] ?? null;
$episode = $id ? fetch("SELECT * FROM episodes WHERE id = ?", [$id]) : null;
?>
<div class="admin-header">
    <h1><?= $episode ? 'Edit' : 'Add' ?> Episode</h1>
    <a href="admin.php?page=episodes&podcast=<?= $podcastId ?>" class="btn">← Back</a>
</div>
<form id="episode-form" class="form-card">
    <input type="hidden" name="id" value="<?= $episode['id'] ?? '' ?>">
    <input type="hidden" name="podcast_id" value="<?= $podcastId ?>">
    <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" value="<?= e($episode['title'] ?? '') ?>" required>
    </div>
    <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="4"><?= e($episode['description'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
        <label>Audio URL</label>
        <input type="text" name="audio_url" value="<?= e($episode['audio_url'] ?? '') ?>" placeholder="https://...">
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Duration (seconds)</label>
            <input type="number" name="duration" value="<?= $episode['duration'] ?? 0 ?>">
        </div>
        <div class="form-group">
            <label>Publish Date</label>
            <input type="datetime-local" name="published_at" value="<?= $episode['published_at'] ? date('Y-m-d\TH:i', strtotime($episode['published_at'])) : '' ?>">
        </div>
    </div>
    <label class="checkbox"><input type="checkbox" name="active" value="1" <?= ($episode['active'] ?? 1) ? 'checked' : '' ?>> Active</label>
    <button type="submit" class="btn btn-primary">Save Episode</button>
</form>
<script>
document.getElementById('episode-form').onsubmit = async e => {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(e.target));
    data.active = data.active ? 1 : 0;
    const res = await fetch('admin.php?ajax=save-episode', { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(data) });
    if ((await res.json()).success) location.href = 'admin.php?page=episodes&podcast=' + data.podcast_id;
};
</script>
