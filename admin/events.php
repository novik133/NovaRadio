<?php $events = fetchAll("SELECT * FROM events ORDER BY event_date DESC"); ?>
<div class="card">
    <div class="card-header"><h3>Events</h3><a href="admin.php?page=event-edit" class="btn btn-primary">Add Event</a></div>
    <table class="table">
        <thead><tr><th>Image</th><th>Title</th><th>Date</th><th>Location</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($events as $ev): ?>
            <tr>
                <td><img src="<?= e($ev['image'] ?: 'assets/img/placeholder.png') ?>" class="thumb"></td>
                <td><?= e($ev['title']) ?></td>
                <td><?= date('M j, Y H:i', strtotime($ev['event_date'])) ?></td>
                <td><?= e($ev['location']) ?></td>
                <td><span class="badge <?= $ev['active'] ? 'badge-success' : 'badge-muted' ?>"><?= $ev['active'] ? 'Active' : 'Inactive' ?></span></td>
                <td>
                    <a href="admin.php?page=event-edit&id=<?= $ev['id'] ?>" class="btn btn-sm">Edit</a>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem('event', <?= $ev['id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
