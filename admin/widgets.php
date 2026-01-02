<?php $widgets = fetchAll("SELECT * FROM widgets ORDER BY location, sort_order"); ?>
<div class="card">
    <div class="card-header"><h3>Footer Widgets</h3><a href="admin.php?page=widget-edit" class="btn btn-primary">Add Widget</a></div>
    <table class="table">
        <thead><tr><th>Location</th><th>Title</th><th>Type</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($widgets as $w): ?>
            <tr>
                <td><span class="badge"><?= e($w['location']) ?></span></td>
                <td><?= e($w['title'] ?: '(No title)') ?></td>
                <td><?= e($w['widget_type']) ?></td>
                <td><?= $w['sort_order'] ?></td>
                <td><span class="badge <?= $w['active'] ? 'badge-success' : 'badge-muted' ?>"><?= $w['active'] ? 'Active' : 'Inactive' ?></span></td>
                <td>
                    <a href="admin.php?page=widget-edit&id=<?= $w['id'] ?>" class="btn btn-sm">Edit</a>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem('widget', <?= $w['id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="info-box mt-2">
    <h4>Widget Locations</h4>
    <p><strong>footer_1</strong> - Left column (usually about/logo)<br>
    <strong>footer_2</strong> - Middle column (usually links)<br>
    <strong>footer_3</strong> - Right column (usually contact)</p>
</div>
