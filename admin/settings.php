<?php
$settings = [];
foreach (fetchAll("SELECT `key`, value FROM settings") as $s) $settings[$s['key']] = $s['value'];
?>
<div class="card">
    <form id="settings-form" class="form form-wide">
        <div class="form-section">
            <h3>General</h3>
            <div class="form-row">
                <div class="form-group"><label>Site Name</label><input type="text" name="site_name" value="<?= e($settings['site_name'] ?? '') ?>"></div>
                <div class="form-group"><label>Site URL</label><input type="url" name="site_url" value="<?= e($settings['site_url'] ?? '') ?>"></div>
            </div>
            <div class="form-group"><label>Site Description</label><textarea name="site_description" rows="2"><?= e($settings['site_description'] ?? '') ?></textarea></div>
        </div>
        
        <div class="form-section">
            <h3>Contact Information</h3>
            <div class="form-row">
                <div class="form-group"><label>Email</label><input type="email" name="contact_email" value="<?= e($settings['contact_email'] ?? '') ?>"></div>
                <div class="form-group"><label>Phone</label><input type="text" name="contact_phone" value="<?= e($settings['contact_phone'] ?? '') ?>"></div>
            </div>
            <div class="form-group"><label>Address</label><textarea name="contact_address" rows="2"><?= e($settings['contact_address'] ?? '') ?></textarea></div>
        </div>
        
        <div class="form-section">
            <h3>SEO</h3>
            <div class="form-group"><label>Meta Keywords</label><input type="text" name="meta_keywords" value="<?= e($settings['meta_keywords'] ?? '') ?>"></div>
            <div class="form-group"><label>Meta Author</label><input type="text" name="meta_author" value="<?= e($settings['meta_author'] ?? '') ?>"></div>
        </div>
        
        <div class="form-section">
            <h3>Integrations</h3>
            <div class="form-group"><label>Google Analytics ID</label><input type="text" name="google_analytics" value="<?= e($settings['google_analytics'] ?? '') ?>" placeholder="G-XXXXXXXXXX"></div>
        </div>
        
        <div class="form-section">
            <h3>Advanced</h3>
            <div class="form-group"><label>Custom Head Code</label><textarea name="custom_head_code" rows="4" class="code"><?= e($settings['custom_head_code'] ?? '') ?></textarea><small>Added before &lt;/head&gt;</small></div>
            <div class="form-group"><label>Custom Footer Code</label><textarea name="custom_footer_code" rows="4" class="code"><?= e($settings['custom_footer_code'] ?? '') ?></textarea><small>Added before &lt;/body&gt;</small></div>
        </div>
        
        <div class="form-actions"><button type="submit" class="btn btn-primary">Save Settings</button></div>
    </form>
</div>
<script>
document.getElementById('settings-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(e.target));
    const res = await fetch('admin.php?ajax=save-settings', {method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(data)});
    if ((await res.json()).success) alert('Settings saved!');
});
</script>
