<?php
requireLogin();
$podcastId = $_GET['podcast'] ?? null;
$podcast = $podcastId ? fetch("SELECT * FROM podcasts WHERE id = ?", [$podcastId]) : null;
$episodes = $podcastId ? fetchAll("SELECT * FROM episodes WHERE podcast_id = ? ORDER BY published_at DESC", [$podcastId]) : [];
?>
<div class="admin-header">
    <h1>Episodes<?= $podcast ? ' - ' . e($podcast['title']) : '' ?></h1>
    <?php if ($podcast): ?><a href="admin.php?page=episode-edit&podcast=<?= $podcastId ?>" class="btn btn-primary">Add Episode</a><?php endif; ?>
</div>
<?php if (!$podcast): ?>
<p>Select a podcast first: <a href="admin.php?page=podcasts">View Podcasts</a></p>
<?php else: ?>
<table class="table">
    <thead><tr><th>Title</th><th>Duration</th><th>Downloads</th><th>Published</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($episodes as $ep): ?>
    <tr>
        <td><?= e($ep['title']) ?></td>
        <td><?= floor($ep['duration'] / 60) ?>:<?= str_pad($ep['duration'] % 60, 2, '0', STR_PAD_LEFT) ?></td>
        <td><?= $ep['downloads'] ?></td>
        <td><?= $ep['published_at'] ? date('M j, Y', strtotime($ep['published_at'])) : '-' ?></td>
        <td><span class="badge <?= $ep['active'] ? 'badge-success' : 'badge-secondary' ?>"><?= $ep['active'] ? 'Active' : 'Draft' ?></span></td>
        <td>
            <a href="admin.php?page=episode-edit&podcast=<?= $podcastId ?>&id=<?= $ep['id'] ?>" class="btn btn-sm">Edit</a>
            <button onclick="deleteItem('episode', <?= $ep['id'] ?>)" class="btn btn-sm btn-danger">Delete</button>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
