<?php $items = fetchAll("SELECT * FROM orders ORDER BY created_at DESC LIMIT 100"); ?>
<div class="card">
    <div class="card-header"><h3>📦 Orders</h3></div>
    <table class="table">
        <thead><tr><th>Order #</th><th>Customer</th><th>Items</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($items as $o): $items_count = count(json_decode($o['items'], true) ?: []); ?>
            <tr>
                <td><strong><?= e($o['order_number']) ?></strong></td>
                <td><?= e($o['customer_name']) ?><br><small><?= e($o['customer_email']) ?></small></td>
                <td><?= $items_count ?> item<?= $items_count != 1 ? 's' : '' ?></td>
                <td><strong>$<?= number_format($o['total'], 2) ?></strong></td>
                <td><span class="badge badge-<?= $o['payment_status'] ?>"><?= ucfirst($o['payment_status']) ?></span></td>
                <td><span class="badge badge-<?= $o['status'] ?>"><?= ucfirst($o['status']) ?></span></td>
                <td><?= date('M j, Y', strtotime($o['created_at'])) ?></td>
                <td><a href="admin.php?page=order-view&id=<?= $o['id'] ?>" class="btn btn-sm">View</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
