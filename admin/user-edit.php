<?php 
$u = isset($_GET['id']) ? fetch("SELECT * FROM admins WHERE id = ?", [$_GET['id']]) : null;
$perms = $u ? json_decode($u['permissions'] ?? '{}', true) : [];
?>
<div class="card">
    <div class="card-header"><h3><?= $u ? 'Edit' : 'Add' ?> User</h3></div>
    <form id="user-form" class="form">
        <input type="hidden" name="id" value="<?= $u['id'] ?? '' ?>">
        <div class="row-2">
            <div class="form-group"><label>Username *</label><input type="text" name="username" value="<?= e($u['username'] ?? '') ?>" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" value="<?= e($u['email'] ?? '') ?>"></div>
        </div>
        <div class="form-group"><label>Password <?= $u ? '(leave blank to keep)' : '*' ?></label><input type="password" name="password" <?= $u ? '' : 'required' ?>></div>
        <div class="form-group">
            <label>Role</label>
            <select name="role" id="role-select" onchange="togglePerms()">
                <option value="super_admin" <?= ($u['role'] ?? '') == 'super_admin' ? 'selected' : '' ?>>Super Admin (Full Access)</option>
                <option value="admin" <?= ($u['role'] ?? '') == 'admin' ? 'selected' : '' ?>>Admin (Manage Content & Users)</option>
                <option value="moderator" <?= ($u['role'] ?? '') == 'moderator' ? 'selected' : '' ?>>Moderator (Chat & Comments)</option>
                <option value="editor" <?= ($u['role'] ?? '') == 'editor' ? 'selected' : '' ?>>Editor (Content Only)</option>
            </select>
        </div>
        
        <div id="perms-section" class="perms-grid">
            <h4>Permissions</h4>
            <div class="perm-group">
                <h5>Content</h5>
                <label class="checkbox"><input type="checkbox" name="perm_stations" <?= !empty($perms['stations']) ? 'checked' : '' ?>> Stations</label>
                <label class="checkbox"><input type="checkbox" name="perm_shows" <?= !empty($perms['shows']) ? 'checked' : '' ?>> Shows</label>
                <label class="checkbox"><input type="checkbox" name="perm_djs" <?= !empty($perms['djs']) ? 'checked' : '' ?>> DJs</label>
                <label class="checkbox"><input type="checkbox" name="perm_schedule" <?= !empty($perms['schedule']) ? 'checked' : '' ?>> Schedule</label>
                <label class="checkbox"><input type="checkbox" name="perm_events" <?= !empty($perms['events']) ? 'checked' : '' ?>> Events</label>
                <label class="checkbox"><input type="checkbox" name="perm_pages" <?= !empty($perms['pages']) ? 'checked' : '' ?>> Pages</label>
                <label class="checkbox"><input type="checkbox" name="perm_blog" <?= !empty($perms['blog']) ? 'checked' : '' ?>> Blog</label>
                <label class="checkbox"><input type="checkbox" name="perm_podcasts" <?= !empty($perms['podcasts']) ? 'checked' : '' ?>> Podcasts</label>
                <label class="checkbox"><input type="checkbox" name="perm_galleries" <?= !empty($perms['galleries']) ? 'checked' : '' ?>> Galleries</label>
            </div>
            <div class="perm-group">
                <h5>AzuraCast</h5>
                <label class="checkbox"><input type="checkbox" name="perm_azuracast" <?= !empty($perms['azuracast']) ? 'checked' : '' ?>> Full Control</label>
                <label class="checkbox"><input type="checkbox" name="perm_media" <?= !empty($perms['media']) ? 'checked' : '' ?>> Media Upload</label>
                <label class="checkbox"><input type="checkbox" name="perm_playlists" <?= !empty($perms['playlists']) ? 'checked' : '' ?>> Playlists</label>
            </div>
            <div class="perm-group">
                <h5>Communication</h5>
                <label class="checkbox"><input type="checkbox" name="perm_chat" <?= !empty($perms['chat']) ? 'checked' : '' ?>> Chat Admin</label>
                <label class="checkbox"><input type="checkbox" name="perm_requests" <?= !empty($perms['requests']) ? 'checked' : '' ?>> Requests</label>
                <label class="checkbox"><input type="checkbox" name="perm_messages" <?= !empty($perms['messages']) ? 'checked' : '' ?>> Messages</label>
                <label class="checkbox"><input type="checkbox" name="perm_comments" <?= !empty($perms['comments']) ? 'checked' : '' ?>> Comments</label>
            </div>
            <div class="perm-group">
                <h5>System</h5>
                <label class="checkbox"><input type="checkbox" name="perm_users" <?= !empty($perms['users']) ? 'checked' : '' ?>> Users</label>
                <label class="checkbox"><input type="checkbox" name="perm_settings" <?= !empty($perms['settings']) ? 'checked' : '' ?>> Settings</label>
                <label class="checkbox"><input type="checkbox" name="perm_appearance" <?= !empty($perms['appearance']) ? 'checked' : '' ?>> Appearance</label>
            </div>
        </div>
        
        <div class="form-group">
            <label class="checkbox"><input type="checkbox" name="is_op" <?= !empty($u['is_op']) ? 'checked' : '' ?>> Chat Operator (OP)</label>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save User</button>
            <a href="admin.php?page=users" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
<script>
function togglePerms() {
    const role = document.getElementById('role-select').value;
    document.getElementById('perms-section').style.display = (role === 'super_admin') ? 'none' : 'grid';
}
togglePerms();

document.getElementById('user-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const perms = {};
    form.querySelectorAll('[name^="perm_"]').forEach(cb => {
        perms[cb.name.replace('perm_', '')] = cb.checked;
    });
    const res = await fetch('admin.php?ajax=save-user', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({
            id: form.id.value || null,
            username: form.username.value,
            email: form.email.value,
            password: form.password.value,
            role: form.role.value,
            permissions: perms,
            is_op: form.is_op.checked
        })
    });
    if ((await res.json()).success) window.location.href = 'admin.php?page=users';
});
</script>
