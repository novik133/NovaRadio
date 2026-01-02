<?php $items = fetchAll("SELECT * FROM sponsors ORDER BY FIELD(tier,'platinum','gold','silver','bronze','partner'), sort_order"); ?>
<div class="card">
    <div class="card-header"><h3>🤝 Sponsors & Partners</h3><a href="admin.php?page=sponsor-edit" class="btn btn-primary">Add Sponsor</a></div>
    <table class="table">
        <thead><tr><th>Logo</th><th>Name</th><th>Tier</th><th>Website</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($items as $s): ?>
            <tr>
                <td><?php if ($s['logo']): ?><img src="<?= e($s['logo']) ?>" class="thumb"><?php endif; ?></td>
                <td><strong><?= e($s['name']) ?></strong></td>
                <td><span class="badge badge-<?= $s['tier'] ?>"><?= ucfirst($s['tier']) ?></span></td>
                <td><?= $s['website'] ? '<a href="'.e($s['website']).'" target="_blank">Visit</a>' : '-' ?></td>
                <td><span class="badge <?= $s['active'] ? 'badge-success' : '' ?>"><?= $s['active'] ? 'Active' : 'Hidden' ?></span></td>
                <td>
                    <a href="admin.php?page=sponsor-edit&id=<?= $s['id'] ?>" class="btn btn-sm">Edit</a>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem('sponsor', <?= $s['id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
