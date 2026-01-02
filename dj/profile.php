<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $currentPass = $_POST['current_password'] ?? '';
    $newPass = $_POST['new_password'] ?? '';
    
    $updates = ['name' => $name, 'bio' => $bio];
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = 'dj_' . $dj['id'] . '_' . time() . '.' . $ext;
        if (move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $filename)) {
            $updates['image'] = 'uploads/' . $filename;
        }
    }
    
    // Handle password change
    if ($newPass) {
        if (password_verify($currentPass, $dj['password'])) {
            $updates['password'] = password_hash($newPass, PASSWORD_ARGON2ID);
            $passMsg = 'Password updated!';
        } else {
            $passError = 'Current password incorrect';
        }
    }
    
    update('djs', $updates, 'id = ?', [$dj['id']]);
    $dj = fetch("SELECT * FROM djs WHERE id = ?", [$dj['id']]);
    $success = 'Profile updated!';
}

$social = json_decode($dj['social_links'], true) ?: [];
?>

<div class="card">
    <div class="card-header"><h3>👤 My Profile</h3></div>
    <div class="card-body">
        <?php if (isset($success)): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>
        <?php if (isset($passMsg)): ?><div class="alert alert-success"><?= e($passMsg) ?></div><?php endif; ?>
        <?php if (isset($passError)): ?><div class="alert alert-danger"><?= e($passError) ?></div><?php endif; ?>
        
        <form method="post" enctype="multipart/form-data">
            <div class="profile-header">
                <img src="<?= e($dj['image'] ?: 'assets/img/dj-placeholder.png') ?>" class="profile-avatar">
                <input type="file" name="image" accept="image/*">
            </div>
            
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="<?= e($dj['name']) ?>" required>
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" value="<?= e($dj['email']) ?>" disabled>
                <small>Contact admin to change email</small>
            </div>
            
            <div class="form-group">
                <label>Bio</label>
                <textarea name="bio" rows="4"><?= e($dj['bio']) ?></textarea>
            </div>
            
            <h4>Change Password</h4>
            <div class="form-row">
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" name="current_password">
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password">
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3>🔑 Permissions</h3></div>
    <div class="card-body">
        <div class="permissions-list">
            <div class="permission-item">
                <span>Can Stream Live</span>
                <span class="badge <?= $dj['can_stream'] ? 'badge-success' : 'badge-danger' ?>"><?= $dj['can_stream'] ? 'Yes' : 'No' ?></span>
            </div>
            <div class="permission-item">
                <span>Can Upload Media</span>
                <span class="badge <?= $dj['can_upload'] ? 'badge-success' : 'badge-danger' ?>"><?= $dj['can_upload'] ? 'Yes' : 'No' ?></span>
            </div>
            <div class="permission-item">
                <span>Can Manage Playlists</span>
                <span class="badge <?= $dj['can_playlist'] ? 'badge-success' : 'badge-danger' ?>"><?= $dj['can_playlist'] ? 'Yes' : 'No' ?></span>
            </div>
        </div>
    </div>
</div>
