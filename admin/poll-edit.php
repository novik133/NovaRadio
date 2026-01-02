<?php
requireLogin();
$id = $_GET['id'] ?? null;
$poll = $id ? fetch("SELECT * FROM polls WHERE id = ?", [$id]) : null;
$options = $id ? fetchAll("SELECT * FROM poll_options WHERE poll_id = ?", [$id]) : [];
?>
<div class="admin-header">
    <h1><?= $poll ? 'Edit' : 'Add' ?> Poll</h1>
    <a href="admin.php?page=polls" class="btn">← Back</a>
</div>
<form id="poll-form" class="form-card">
    <input type="hidden" name="id" value="<?= $poll['id'] ?? '' ?>">
    <div class="form-group">
        <label>Question</label>
        <input type="text" name="question" value="<?= e($poll['question'] ?? '') ?>" required>
    </div>
    <div class="form-group">
        <label>Options</label>
        <div id="options-list">
            <?php foreach ($options as $i => $o): ?>
            <div class="option-row"><input type="text" name="options[]" value="<?= e($o['option_text']) ?>" required><button type="button" onclick="this.parentElement.remove()">×</button></div>
            <?php endforeach; ?>
            <?php if (!$options): ?>
            <div class="option-row"><input type="text" name="options[]" placeholder="Option 1" required><button type="button" onclick="this.parentElement.remove()">×</button></div>
            <div class="option-row"><input type="text" name="options[]" placeholder="Option 2" required><button type="button" onclick="this.parentElement.remove()">×</button></div>
            <?php endif; ?>
        </div>
        <button type="button" onclick="addOption()" class="btn btn-sm">+ Add Option</button>
    </div>
    <div class="form-group">
        <label>End Date (optional)</label>
        <input type="datetime-local" name="ends_at" value="<?= $poll['ends_at'] ? date('Y-m-d\TH:i', strtotime($poll['ends_at'])) : '' ?>">
    </div>
    <div class="form-row">
        <label class="checkbox"><input type="checkbox" name="multiple" value="1" <?= ($poll['multiple'] ?? 0) ? 'checked' : '' ?>> Allow multiple votes</label>
        <label class="checkbox"><input type="checkbox" name="active" value="1" <?= ($poll['active'] ?? 1) ? 'checked' : '' ?>> Active</label>
    </div>
    <button type="submit" class="btn btn-primary">Save Poll</button>
</form>
<script>
function addOption() {
    const div = document.createElement('div');
    div.className = 'option-row';
    div.innerHTML = '<input type="text" name="options[]" required><button type="button" onclick="this.parentElement.remove()">×</button>';
    document.getElementById('options-list').appendChild(div);
}
document.getElementById('poll-form').onsubmit = async e => {
    e.preventDefault();
    const fd = new FormData(e.target);
    const data = { id: fd.get('id'), question: fd.get('question'), ends_at: fd.get('ends_at'), multiple: fd.get('multiple') ? 1 : 0, active: fd.get('active') ? 1 : 0, options: fd.getAll('options[]') };
    const res = await fetch('admin.php?ajax=save-poll', { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(data) });
    if ((await res.json()).success) location.href = 'admin.php?page=polls';
};
</script>
