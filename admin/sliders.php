<?php $sliders = fetchAll("SELECT * FROM sliders ORDER BY sort_order"); ?>
<div class="card">
    <div class="card-header"><h3>Homepage Sliders</h3><a href="admin.php?page=slider-edit" class="btn btn-primary">Add Slider</a></div>
    <table class="table">
        <thead><tr><th>Image</th><th>Title</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($sliders as $s): ?>
            <tr>
                <td><img src="<?= e($s['image'] ?: 'assets/img/placeholder.png') ?>" class="thumb-wide"></td>
                <td><?= e($s['title']) ?></td>
                <td><?= $s['sort_order'] ?></td>
                <td><span class="badge <?= $s['active'] ? 'badge-success' : 'badge-muted' ?>"><?= $s['active'] ? 'Active' : 'Inactive' ?></span></td>
                <td>
                    <a href="admin.php?page=slider-edit&id=<?= $s['id'] ?>" class="btn btn-sm">Edit</a>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem('slider', <?= $s['id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
