<?php
$mySchedule = fetchAll("SELECT s.*, sh.name as show_name, st.name as station_name 
    FROM schedule s 
    LEFT JOIN shows sh ON s.show_id = sh.id 
    LEFT JOIN stations st ON s.station_id = st.id 
    WHERE s.dj_id = ? 
    ORDER BY s.day_of_week, s.start_time", [$dj['id']]);

$days = ['', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
?>

<div class="card">
    <div class="card-header"><h3>📅 My Schedule</h3></div>
    <div class="card-body">
        <?php if (empty($mySchedule)): ?>
        <p class="text-muted">No scheduled shows assigned to you.</p>
        <?php else: ?>
        <table class="table">
            <thead><tr><th>Day</th><th>Time</th><th>Show</th><th>Station</th></tr></thead>
            <tbody>
            <?php foreach ($mySchedule as $s): ?>
            <tr>
                <td><?= $days[$s['day_of_week']] ?></td>
                <td><?= date('g:i A', strtotime($s['start_time'])) ?> - <?= date('g:i A', strtotime($s['end_time'])) ?></td>
                <td><?= e($s['show_name'] ?? 'N/A') ?></td>
                <td><?= e($s['station_name'] ?? 'All') ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3>📡 AzuraCast Schedule</h3></div>
    <div class="card-body">
        <div id="ac-schedule"></div>
    </div>
</div>

<script>
async function loadAcSchedule() {
    const schedule = await fetch('dj.php?ajax=schedule&station='+STATION_ID).then(r=>r.json());
    if (Array.isArray(schedule)) {
        document.getElementById('ac-schedule').innerHTML = schedule.length ? schedule.map(s => `
            <div class="schedule-item">
                <span class="schedule-time">${new Date(s.start*1000).toLocaleString()}</span>
                <span class="schedule-name">${s.name}</span>
                <span class="badge">${s.type}</span>
            </div>
        `).join('') : '<p class="text-muted">No scheduled items</p>';
    }
}
loadAcSchedule();
</script>
