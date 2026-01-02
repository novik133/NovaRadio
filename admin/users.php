<?php $users = fetchAll("SELECT id, username, email, role, is_op, created_at FROM admins ORDER BY FIELD(role,'super_admin','admin','moderator','editor'), username"); ?>
<div class="card">
    <div class="card-header"><h3>Users & Permissions</h3><a href="admin.php?page=user-edit" class="btn btn-primary">Add User</a></div>
    <table class="table">
        <thead><tr><th>Username</th><th>Email</th><th>Role</th><th>Chat OP</th><th>Created</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($users as $u): ?>
            <tr>
                <td><strong><?= e($u['username']) ?></strong></td>
                <td><?= e($u['email'] ?? '-') ?></td>
                <td><span class="badge badge-<?= $u['role'] ?>"><?= ucfirst(str_replace('_', ' ', $u['role'] ?? 'editor')) ?></span></td>
                <td><?= $u['is_op'] ? '✓ OP' : '-' ?></td>
                <td><?= date('M j, Y', strtotime($u['created_at'])) ?></td>
                <td>
                    <a href="admin.php?page=user-edit&id=<?= $u['id'] ?>" class="btn btn-sm">Edit</a>
                    <?php if ($u['id'] != $_SESSION['admin_id']): ?>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem('user', <?= $u['id'] ?>)">Delete</button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="card">
    <div class="card-header"><h3>Role Descriptions</h3></div>
    <div class="card-body">
        <div class="role-info">
            <p><span class="badge badge-super_admin">Super Admin</span> Full access to everything including AzuraCast control, user management, and all settings.</p>
            <p><span class="badge badge-admin">Admin</span> Manage content, users, and most settings. Can be restricted with permissions.</p>
            <p><span class="badge badge-moderator">Moderator</span> Manage chat, comments, requests, and messages. Limited content access.</p>
            <p><span class="badge badge-editor">Editor</span> Create and edit content only. No access to settings or users.</p>
        </div>
    </div>
</div>
