<?php 
$o = fetch("SELECT * FROM orders WHERE id = ?", [$_GET['id'] ?? 0]);
if (!$o) { echo '<div class="alert alert-danger">Order not found</div>'; return; }
$items = json_decode($o['items'], true) ?: [];
?>
<div class="row-2">
    <div class="card">
        <div class="card-header"><h3>Order #<?= e($o['order_number']) ?></h3></div>
        <div class="card-body">
            <p><strong>Date:</strong> <?= date('F j, Y g:i A', strtotime($o['created_at'])) ?></p>
            <p><strong>Status:</strong> 
                <select id="order-status" onchange="updateStatus()">
                    <?php foreach (['pending','processing','shipped','delivered','cancelled'] as $s): ?>
                    <option value="<?= $s ?>" <?= $o['status'] == $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p><strong>Payment:</strong> <span class="badge badge-<?= $o['payment_status'] ?>"><?= ucfirst($o['payment_status']) ?></span> (<?= e($o['payment_method'] ?: 'N/A') ?>)</p>
            <hr>
            <h4>Items</h4>
            <table class="table">
                <thead><tr><th>Product</th><th>Qty</th><th>Price</th></tr></thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr><td><?= e($item['name']) ?></td><td><?= $item['qty'] ?></td><td>$<?= number_format($item['price'], 2) ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div style="text-align:right">
                <p>Subtotal: $<?= number_format($o['subtotal'], 2) ?></p>
                <p>Shipping: $<?= number_format($o['shipping'], 2) ?></p>
                <p>Tax: $<?= number_format($o['tax'], 2) ?></p>
                <p><strong>Total: $<?= number_format($o['total'], 2) ?></strong></p>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3>Customer</h3></div>
        <div class="card-body">
            <p><strong>Name:</strong> <?= e($o['customer_name']) ?></p>
            <p><strong>Email:</strong> <?= e($o['customer_email']) ?></p>
            <p><strong>Phone:</strong> <?= e($o['customer_phone'] ?: 'N/A') ?></p>
            <hr>
            <h4>Shipping Address</h4>
            <p><?= nl2br(e($o['shipping_address'] ?: 'N/A')) ?></p>
            <?php if ($o['notes']): ?><hr><h4>Notes</h4><p><?= nl2br(e($o['notes'])) ?></p><?php endif; ?>
        </div>
    </div>
</div>
<a href="admin.php?page=orders" class="btn btn-outline">← Back to Orders</a>
<script>
async function updateStatus() {
    await fetch('admin.php?ajax=update-order', {
        method: 'POST', headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id: <?= $o['id'] ?>, status: document.getElementById('order-status').value})
    });
}
</script>
