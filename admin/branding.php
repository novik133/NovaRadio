<?php
$settings = [];
foreach (fetchAll("SELECT `key`, value FROM settings") as $s) $settings[$s['key']] = $s['value'];
?>
<div class="card">
    <form id="branding-form" class="form form-wide">
        <div class="form-section">
            <h3>Logo</h3>
            <div class="form-group">
                <label>Logo Image</label>
                <div class="image-upload">
                    <input type="hidden" name="logo_url" value="<?= e($settings['logo_url'] ?? '') ?>">
                    <img id="logo-preview" src="<?= e($settings['logo_url'] ?? 'assets/img/placeholder.png') ?>" class="preview-img-wide">
                    <input type="file" id="logo-input" accept="image/*">
                    <button type="button" class="btn btn-sm" onclick="document.getElementById('logo-input').click()">Upload Logo</button>
                    <button type="button" class="btn btn-sm btn-outline" onclick="document.querySelector('[name=logo_url]').value='';document.getElementById('logo-preview').src='assets/img/placeholder.png'">Remove</button>
                </div>
                <small>Leave empty to use text logo</small>
            </div>
            <div class="form-group"><label>Logo Text (if no image)</label><input type="text" name="logo_text" value="<?= e($settings['logo_text'] ?? '') ?>"></div>
            <div class="form-group">
                <label>Favicon</label>
                <div class="image-upload">
                    <input type="hidden" name="favicon_url" value="<?= e($settings['favicon_url'] ?? '') ?>">
                    <img id="favicon-preview" src="<?= e($settings['favicon_url'] ?? 'assets/img/placeholder.png') ?>" class="preview-img-sm">
                    <input type="file" id="favicon-input" accept="image/*">
                    <button type="button" class="btn btn-sm" onclick="document.getElementById('favicon-input').click()">Upload</button>
                </div>
            </div>
        </div>
        
        <div class="form-section">
            <h3>Colors</h3>
            <div class="form-row">
                <div class="form-group"><label>Primary Color</label><input type="color" name="primary_color" value="<?= e($settings['primary_color'] ?? '#6366f1') ?>" class="color-input"></div>
            </div>
        </div>
        
        <div class="form-section">
            <h3>Footer</h3>
            <div class="form-group"><label>Copyright Text</label><input type="text" name="copyright_text" value="<?= e($settings['copyright_text'] ?? '') ?>"><small>Use {year} and {site_name} as placeholders</small></div>
            <div class="form-group"><label>Footer Text</label><input type="text" name="footer_text" value="<?= e($settings['footer_text'] ?? '') ?>"></div>
        </div>
        
        <div class="form-section">
            <h3>Social Links</h3>
            <div class="form-row">
                <div class="form-group"><label>Facebook</label><input type="url" name="social_facebook" value="<?= e($settings['social_facebook'] ?? '') ?>"></div>
                <div class="form-group"><label>Instagram</label><input type="url" name="social_instagram" value="<?= e($settings['social_instagram'] ?? '') ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Twitter/X</label><input type="url" name="social_twitter" value="<?= e($settings['social_twitter'] ?? '') ?>"></div>
                <div class="form-group"><label>YouTube</label><input type="url" name="social_youtube" value="<?= e($settings['social_youtube'] ?? '') ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>SoundCloud</label><input type="url" name="social_soundcloud" value="<?= e($settings['social_soundcloud'] ?? '') ?>"></div>
                <div class="form-group"><label>Mixcloud</label><input type="url" name="social_mixcloud" value="<?= e($settings['social_mixcloud'] ?? '') ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>TikTok</label><input type="url" name="social_tiktok" value="<?= e($settings['social_tiktok'] ?? '') ?>"></div>
                <div class="form-group"><label>Discord</label><input type="url" name="social_discord" value="<?= e($settings['social_discord'] ?? '') ?>"></div>
            </div>
        </div>
        
        <div class="form-actions"><button type="submit" class="btn btn-primary">Save Branding</button></div>
    </form>
</div>
<script>
['logo','favicon'].forEach(type => {
    document.getElementById(type+'-input').addEventListener('change', async (e) => {
        const file = e.target.files[0]; if (!file) return;
        const fd = new FormData(); fd.append('image', file);
        const res = await fetch('admin.php?ajax=upload', {method:'POST',body:fd});
        const data = await res.json();
        if (data.success) { document.querySelector(`[name="${type}_url"]`).value = data.url; document.getElementById(type+'-preview').src = data.url; }
    });
});
document.getElementById('branding-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(e.target));
    const res = await fetch('admin.php?ajax=save-settings', {method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(data)});
    if ((await res.json()).success) alert('Branding saved!');
});
</script>
