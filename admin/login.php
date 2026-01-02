<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body class="login-page">
    <div class="login-box">
        <h1><?= e(siteName()) ?></h1>
        <p>Admin Panel</p>
        <form method="POST">
            <div class="form-group"><label>Username</label><input type="text" name="username" required autofocus></div>
            <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
            <?php if (isset($error)): ?><p class="error-msg"><?= e($error) ?></p><?php endif; ?>
        </form>
    </div>
</body>
</html>
