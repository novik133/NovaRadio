<?php
$period = $_GET['period'] ?? '7';
$analytics = fetchAll("SELECT page, SUM(views) as total FROM analytics WHERE date >= DATE_SUB(CURDATE(), INTERVAL ? DAY) GROUP BY page ORDER BY total DESC", [$period]);
$daily = fetchAll("SELECT date, SUM(views) as total FROM analytics WHERE date >= DATE_SUB(CURDATE(), INTERVAL ? DAY) GROUP BY date ORDER BY date", [$period]);
$totalViews = array_sum(array_column($analytics, 'total'));
?>
<div class="card">
    <div class="card-header">
        <h3>Page Views</h3>
        <select onchange="location.href='admin.php?page=analytics&period='+this.value">
            <option value="7" <?= $period=='7'?'selected':'' ?>>Last 7 days</option>
            <option value="30" <?= $period=='30'?'selected':'' ?>>Last 30 days</option>
            <option value="90" <?= $period=='90'?'selected':'' ?>>Last 90 days</option>
        </select>
    </div>
    <div class="card-body">
        <div class="stats-grid">
            <div class="stat-card"><div class="stat-info"><span class="stat-value"><?= number_format($totalViews) ?></span><span class="stat-label">Total Views</span></div></div>
            <div class="stat-card"><div class="stat-info"><span class="stat-value"><?= count($analytics) ?></span><span class="stat-label">Pages Tracked</span></div></div>
        </div>
        
        <h4 class="mt-2">Daily Views</h4>
        <div class="chart-bars">
            <?php $max = max(array_column($daily, 'total') ?: [1]); foreach ($daily as $d): ?>
            <div class="chart-bar">
                <div class="bar" style="height:<?= ($d['total']/$max)*100 ?>%"></div>
                <span><?= date('j', strtotime($d['date'])) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        
        <h4 class="mt-2">Top Pages</h4>
        <table class="table">
            <thead><tr><th>Page</th><th>Views</th></tr></thead>
            <tbody>
                <?php foreach ($analytics as $a): ?>
                <tr><td><?= e($a['page']) ?></td><td><?= number_format($a['total']) ?></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
