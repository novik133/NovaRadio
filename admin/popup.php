<?php
$popup = [
    'enabled' => getSetting('popup_enabled', '0'),
    'title' => getSetting('popup_title', 'Welcome!'),
    'message' => getSetting('popup_message', ''),
    'button_text' => getSetting('popup_button_text', 'Got it'),
    'button_url' => getSetting('popup_button_url', ''),
    'image' => getSetting('popup_image', ''),
    'delay' => getSetting('popup_delay', '3'),
    'show_once' => getSetting('popup_show_once', '1'),
    'id' => getSetting('popup_id', '1'),
];
?>
<div class="card">
    <div class="card-header"><h3>📢 Notification Popup</h3></div>
    <form id="popup-form" class="form">
        <div class="form-group">
            <label>Enable Popup</label>
            <select name="popup_enabled">
                <option value="1" <?= $popup['enabled'] === '1' ? 'selected' : '' ?>>Enabled</option>
                <option value="0" <?= $popup['enabled'] === '0' ? 'selected' : '' ?>>Disabled</option>
            </select>
        </div>
        
        <h4 style="margin:1.5rem 0 1rem;color:var(--primary)">Content</h4>
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="popup_title" value="<?= e($popup['title']) ?>">
        </div>
        <div class="form-group">
            <label>Message</label>
            <textarea name="popup_message" rows="3"><?= e($popup['message']) ?></textarea>
        </div>
        <div class="form-group">
            <label>Image (optional)</label>
            <div class="image-upload">
                <input type="hidden" name="popup_image" value="<?= e($popup['image']) ?>">
                <?php if ($popup['image']): ?><img src="<?= e($popup['image']) ?>" style="max-width:200px;margin-bottom:0.5rem"><?php endif; ?>
                <button type="button" class="btn btn-sm" onclick="uploadImage('popup_image')">Upload Image</button>
            </div>
        </div>
        
        <h4 style="margin:1.5rem 0 1rem;color:var(--primary)">Button</h4>
        <div class="row-2">
            <div class="form-group">
                <label>Button Text</label>
                <input type="text" name="popup_button_text" value="<?= e($popup['button_text']) ?>">
            </div>
            <div class="form-group">
                <label>Button URL (optional)</label>
                <input type="text" name="popup_button_url" value="<?= e($popup['button_url']) ?>" placeholder="Leave empty for close button">
            </div>
        </div>
        
        <h4 style="margin:1.5rem 0 1rem;color:var(--primary)">Behavior</h4>
        <div class="row-3">
            <div class="form-group">
                <label>Delay (seconds)</label>
                <input type="number" name="popup_delay" value="<?= e($popup['delay']) ?>" min="0" max="60">
            </div>
            <div class="form-group">
                <label>Show Once Per User</label>
                <select name="popup_show_once">
                    <option value="1" <?= $popup['show_once'] === '1' ? 'selected' : '' ?>>Yes</option>
                    <option value="0" <?= $popup['show_once'] === '0' ? 'selected' : '' ?>>No (every visit)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Popup ID</label>
                <input type="number" name="popup_id" value="<?= e($popup['id']) ?>" min="1">
                <small style="color:var(--text-muted)">Change to reset "show once"</small>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header"><h3>Preview</h3><button type="button" class="btn btn-sm" onclick="showPreview()">Show Preview</button></div>
    <div class="card-body">
        <div id="popup-preview" style="position:relative;background:var(--bg);border-radius:8px;min-height:300px;overflow:hidden;display:none">
            <div class="notif-popup" style="position:absolute;display:flex">
                <div class="notif-overlay"></div>
                <div class="notif-content">
                    <button class="notif-close">×</button>
                    <img id="preview-image" src="" class="notif-image" style="display:none">
                    <h3 id="preview-title">Welcome!</h3>
                    <p id="preview-message">Check out our latest shows!</p>
                    <button class="notif-btn" id="preview-btn">Got it</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('popup-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const fd = new FormData(e.target);
    const data = Object.fromEntries(fd);
    await fetch('admin.php?ajax=save-settings', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
    });
    alert('Settings saved!');
});

const form = document.getElementById('popup-form');

function showPreview() {
    updatePreview();
    document.getElementById('popup-preview').style.display = 'block';
}

function updatePreview() {
    document.getElementById('preview-title').textContent = form.popup_title.value;
    document.getElementById('preview-message').textContent = form.popup_message.value;
    document.getElementById('preview-btn').textContent = form.popup_button_text.value;
    const img = document.getElementById('preview-image');
    const imgVal = form.popup_image.value;
    if (imgVal) { img.src = imgVal; img.style.display = 'block'; }
    else { img.style.display = 'none'; }
}

form.querySelectorAll('input, textarea').forEach(el => el.addEventListener('input', updatePreview));
</script>
