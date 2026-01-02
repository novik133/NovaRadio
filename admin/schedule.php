<?php
$schedule = fetchAll("SELECT s.*, sh.name as show_name, d.name as dj_name FROM schedule s LEFT JOIN shows sh ON s.show_id = sh.id LEFT JOIN djs d ON s.dj_id = d.id ORDER BY s.day_of_week, s.start_time");
$shows = fetchAll("SELECT id, name FROM shows WHERE active = 1 ORDER BY name");
$djs = fetchAll("SELECT id, name FROM djs WHERE active = 1 ORDER BY name");
$days = ['','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
?>
<div class="card">
    <div class="card-header">
        <h3>Weekly Schedule</h3>
        <button class="btn btn-primary" onclick="openModal()">Add Slot</button>
    </div>
    <table class="table">
        <thead><tr><th>Day</th><th>Time</th><th>Show</th><th>DJ</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($schedule as $slot): ?>
            <tr>
                <td><?= $days[$slot['day_of_week']] ?></td>
                <td><?= substr($slot['start_time'], 0, 5) ?> - <?= substr($slot['end_time'], 0, 5) ?></td>
                <td><?= e($slot['show_name']) ?></td>
                <td><?= e($slot['dj_name'] ?? '-') ?></td>
                <td>
                    <button class="btn btn-sm" onclick='editSlot(<?= json_encode($slot) ?>)'>Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem('schedule', <?= $slot['id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div id="modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modal-title">Add Schedule Slot</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form id="schedule-form" class="form">
            <input type="hidden" name="id">
            <div class="form-group">
                <label>Day *</label>
                <select name="day_of_week" required>
                    <?php for ($i = 1; $i <= 7; $i++): ?><option value="<?= $i ?>"><?= $days[$i] ?></option><?php endfor; ?>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Start Time *</label><input type="time" name="start_time" required></div>
                <div class="form-group"><label>End Time *</label><input type="time" name="end_time" required></div>
            </div>
            <div class="form-group">
                <label>Show *</label>
                <select name="show_id" required>
                    <?php foreach ($shows as $s): ?><option value="<?= $s['id'] ?>"><?= e($s['name']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>DJ</label>
                <select name="dj_id">
                    <option value="">-- None --</option>
                    <?php foreach ($djs as $d): ?><option value="<?= $d['id'] ?>"><?= e($d['name']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>
<script>
function openModal() {
    document.getElementById('modal-title').textContent = 'Add Schedule Slot';
    document.getElementById('schedule-form').reset();
    document.querySelector('[name="id"]').value = '';
    document.getElementById('modal').classList.add('open');
}
function closeModal() { document.getElementById('modal').classList.remove('open'); }
function editSlot(slot) {
    document.getElementById('modal-title').textContent = 'Edit Schedule Slot';
    const form = document.getElementById('schedule-form');
    form.id.value = slot.id;
    form.day_of_week.value = slot.day_of_week;
    form.start_time.value = slot.start_time.substring(0, 5);
    form.end_time.value = slot.end_time.substring(0, 5);
    form.show_id.value = slot.show_id;
    form.dj_id.value = slot.dj_id || '';
    document.getElementById('modal').classList.add('open');
}
document.getElementById('schedule-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    await fetch('admin.php?ajax=save-schedule', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            id: form.id.value || null,
            day_of_week: form.day_of_week.value,
            start_time: form.start_time.value,
            end_time: form.end_time.value,
            show_id: form.show_id.value,
            dj_id: form.dj_id.value || null
        })
    });
    location.reload();
});
</script>
