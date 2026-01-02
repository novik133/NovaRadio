<?php
$gdpr = [
    'enabled' => getSetting('gdpr_enabled', '1'),
    'title' => getSetting('gdpr_title', 'Cookie Consent'),
    'message' => getSetting('gdpr_message', 'We use cookies to enhance your experience.'),
    'accept_text' => getSetting('gdpr_accept_text', 'Accept'),
    'decline_text' => getSetting('gdpr_decline_text', 'Decline'),
    'privacy_url' => getSetting('gdpr_privacy_url', ''),
    'cookies_url' => getSetting('gdpr_cookies_url', ''),
    'position' => getSetting('gdpr_position', 'bottom'),
    'style' => getSetting('gdpr_style', 'bar'),
];
?>
<div class="card">
    <div class="card-header"><h3>🍪 GDPR / Cookie Consent</h3></div>
    <form id="gdpr-form" class="form">
        <div class="form-group">
            <label>Enable Cookie Consent Popup</label>
            <select name="gdpr_enabled">
                <option value="1" <?= $gdpr['enabled'] === '1' ? 'selected' : '' ?>>Enabled</option>
                <option value="0" <?= $gdpr['enabled'] === '0' ? 'selected' : '' ?>>Disabled</option>
            </select>
        </div>
        
        <h4 style="margin:1.5rem 0 1rem;color:var(--primary)">Content</h4>
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="gdpr_title" value="<?= e($gdpr['title']) ?>">
        </div>
        <div class="form-group">
            <label>Message</label>
            <textarea name="gdpr_message" rows="3"><?= e($gdpr['message']) ?></textarea>
        </div>
        <div class="row-2">
            <div class="form-group">
                <label>Accept Button Text</label>
                <input type="text" name="gdpr_accept_text" value="<?= e($gdpr['accept_text']) ?>">
            </div>
            <div class="form-group">
                <label>Decline Button Text</label>
                <input type="text" name="gdpr_decline_text" value="<?= e($gdpr['decline_text']) ?>">
            </div>
        </div>
        
        <h4 style="margin:1.5rem 0 1rem;color:var(--primary)">Links</h4>
        <div class="row-2">
            <div class="form-group">
                <label>Privacy Policy URL</label>
                <input type="text" name="gdpr_privacy_url" value="<?= e($gdpr['privacy_url']) ?>" placeholder="/page.php?slug=privacy-policy">
            </div>
            <div class="form-group">
                <label>Cookie Policy URL</label>
                <input type="text" name="gdpr_cookies_url" value="<?= e($gdpr['cookies_url']) ?>" placeholder="/page.php?slug=cookies">
            </div>
        </div>
        
        <h4 style="margin:1.5rem 0 1rem;color:var(--primary)">Appearance</h4>
        <div class="row-2">
            <div class="form-group">
                <label>Position</label>
                <select name="gdpr_position">
                    <option value="bottom" <?= $gdpr['position'] === 'bottom' ? 'selected' : '' ?>>Bottom</option>
                    <option value="top" <?= $gdpr['position'] === 'top' ? 'selected' : '' ?>>Top</option>
                </select>
            </div>
            <div class="form-group">
                <label>Style</label>
                <select name="gdpr_style">
                    <option value="bar" <?= $gdpr['style'] === 'bar' ? 'selected' : '' ?>>Full Width Bar</option>
                    <option value="box" <?= $gdpr['style'] === 'box' ? 'selected' : '' ?>>Corner Box</option>
                </select>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header"><h3>Preview</h3></div>
    <div class="card-body">
        <div id="gdpr-preview" style="position:relative;background:var(--bg);border-radius:8px;min-height:150px;overflow:hidden">
            <div class="gdpr-popup gdpr-bottom gdpr-bar" style="position:absolute;display:flex">
                <div class="gdpr-content">
                    <div class="gdpr-text">
                        <strong id="preview-title">Cookie Consent</strong>
                        <p id="preview-message">We use cookies to enhance your experience.</p>
                        <div class="gdpr-links">
                            <a href="#">Privacy Policy</a>
                            <a href="#">Cookie Policy</a>
                        </div>
                    </div>
                    <div class="gdpr-buttons">
                        <button class="gdpr-btn gdpr-accept" id="preview-accept">Accept</button>
                        <button class="gdpr-btn gdpr-decline" id="preview-decline">Decline</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('gdpr-form').addEventListener('submit', async (e) => {
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

// Live preview
const form = document.getElementById('gdpr-form');
const preview = document.querySelector('#gdpr-preview .gdpr-popup');

form.querySelectorAll('input, textarea, select').forEach(el => {
    el.addEventListener('input', updatePreview);
    el.addEventListener('change', updatePreview);
});

function updatePreview() {
    document.getElementById('preview-title').textContent = form.gdpr_title.value;
    document.getElementById('preview-message').textContent = form.gdpr_message.value;
    document.getElementById('preview-accept').textContent = form.gdpr_accept_text.value;
    document.getElementById('preview-decline').textContent = form.gdpr_decline_text.value;
    
    preview.className = 'gdpr-popup gdpr-' + form.gdpr_position.value + ' gdpr-' + form.gdpr_style.value;
    preview.style.display = 'flex';
    preview.style.position = 'absolute';
}
</script>
