<?php 
$shows = fetchAll("SELECT s.*, st.name as station_name FROM shows s LEFT JOIN stations st ON s.station_id = st.id ORDER BY s.name");
$stations = getStations(false);
?>
<div class="card">
    <div class="card-header"><h3>All Shows</h3><a href="admin.php?page=show-edit" class="btn btn-primary">Add Show</a></div>
    <table class="table">
        <thead><tr><th>Image</th><th>Name</th><th>Station</th><th>Genre</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($shows as $show): ?>
            <tr>
                <td><img src="<?= e($show['image'] ?: 'assets/img/placeholder.png') ?>" class="thumb"></td>
                <td><?= e($show['name']) ?></td>
                <td><span class="badge"><?= e($show['station_name'] ?? 'All') ?></span></td>
                <td><?= e($show['genre']) ?></td>
                <td><span class="badge <?= $show['active'] ? 'badge-success' : 'badge-muted' ?>"><?= $show['active'] ? 'Active' : 'Inactive' ?></span></td>
                <td>
                    <a href="admin.php?page=show-edit&id=<?= $show['id'] ?>" class="btn btn-sm">Edit</a>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem('show', <?= $show['id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
