<?php
$headerMenu = fetchAll("SELECT * FROM menu_items WHERE location = 'header' ORDER BY sort_order");
$footerMenu = fetchAll("SELECT * FROM menu_items WHERE location = 'footer' ORDER BY sort_order");
?>
<div class="tabs">
    <button class="tab-btn active" onclick="showTab('header')">Header Menu</button>
    <button class="tab-btn" onclick="showTab('footer')">Footer Menu</button>
</div>

<div id="tab-header" class="tab-content active">
    <div class="card">
        <div class="card-header"><h3>Header Menu Items</h3><button class="btn btn-primary" onclick="openModal('header')">Add Item</button></div>
        <table class="table">
            <thead><tr><th>Label</th><th>URL</th><th>Target</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($headerMenu as $item): ?>
                <tr>
                    <td><?= e($item['label']) ?></td>
                    <td><?= e($item['url']) ?></td>
                    <td><?= e($item['target']) ?></td>
                    <td><?= $item['sort_order'] ?></td>
                    <td><span class="badge <?= $item['active'] ? 'badge-success' : 'badge-muted' ?>"><?= $item['active'] ? 'Active' : 'Inactive' ?></span></td>
                    <td>
                        <button class="btn btn-sm" onclick='editItem(<?= json_encode($item) ?>)'>Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteItem('menu', <?= $item['id'] ?>)">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="tab-footer" class="tab-content">
    <div class="card">
        <div class="card-header"><h3>Footer Menu Items</h3><button class="btn btn-primary" onclick="openModal('footer')">Add Item</button></div>
        <table class="table">
            <thead><tr><th>Label</th><th>URL</th><th>Target</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($footerMenu as $item): ?>
                <tr>
                    <td><?= e($item['label']) ?></td>
                    <td><?= e($item['url']) ?></td>
                    <td><?= e($item['target']) ?></td>
                    <td><?= $item['sort_order'] ?></td>
                    <td><span class="badge <?= $item['active'] ? 'badge-success' : 'badge-muted' ?>"><?= $item['active'] ? 'Active' : 'Inactive' ?></span></td>
                    <td>
                        <button class="btn btn-sm" onclick='editItem(<?= json_encode($item) ?>)'>Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteItem('menu', <?= $item['id'] ?>)">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="modal" class="modal">
    <div class="modal-content">
        <div class="modal-header"><h3 id="modal-title">Add Menu Item</h3><button class="modal-close" onclick="closeModal()">&times;</button></div>
        <form id="menu-form" class="form">
            <input type="hidden" name="id">
            <input type="hidden" name="location" value="header">
            <div class="form-group"><label>Label *</label><input type="text" name="label" required></div>
            <div class="form-group"><label>URL *</label><input type="text" name="url" required></div>
            <div class="form-row">
                <div class="form-group"><label>Target</label><select name="target"><option value="_self">Same Window</option><option value="_blank">New Window</option></select></div>
                <div class="form-group"><label>Sort Order</label><input type="number" name="sort_order" value="0"></div>
            </div>
            <div class="form-group"><label class="checkbox"><input type="checkbox" name="active" checked> Active</label></div>
            <div class="form-actions"><button type="submit" class="btn btn-primary">Save</button><button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button></div>
        </form>
    </div>
</div>

<script>
function showTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    document.querySelector(`[onclick="showTab('${tab}')"]`).classList.add('active');
    document.getElementById('tab-' + tab).classList.add('active');
}
function openModal(location) {
    document.getElementById('modal-title').textContent = 'Add Menu Item';
    document.getElementById('menu-form').reset();
    document.querySelector('[name="id"]').value = '';
    document.querySelector('[name="location"]').value = location;
    document.getElementById('modal').classList.add('open');
}
function closeModal() { document.getElementById('modal').classList.remove('open'); }
function editItem(item) {
    document.getElementById('modal-title').textContent = 'Edit Menu Item';
    const f = document.getElementById('menu-form');
    f.id.value = item.id; f.location.value = item.location; f.label.value = item.label;
    f.url.value = item.url; f.target.value = item.target; f.sort_order.value = item.sort_order;
    f.active.checked = item.active == 1;
    document.getElementById('modal').classList.add('open');
}
document.getElementById('menu-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const f = e.target;
    await fetch('admin.php?ajax=save-menu', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({id:f.id.value||null, location:f.location.value, label:f.label.value, url:f.url.value, target:f.target.value, sort_order:f.sort_order.value, active:f.active.checked?1:0})
    });
    location.reload();
});
</script>
