<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

if (getSetting('chat_enabled', '1') !== '1') {
    echo '<div class="container"><p>Chat is currently disabled.</p></div>';
    exit;
}

$chatUser = $_SESSION['chat_user'] ?? null;
$rooms = fetchAll("SELECT * FROM chat_rooms WHERE active = 1 ORDER BY sort_order, name");
$defaultRoom = fetch("SELECT * FROM chat_rooms WHERE is_default = 1 AND active = 1") ?: ($rooms[0] ?? null);
trackPageView('chat');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/chat.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="chat-wrapper">
            <?php if (!$chatUser): ?>
            <div id="chat-auth" class="chat-auth">
                <h2>Join Chat</h2>
                <div class="tabs">
                    <button class="tab active" data-tab="login">Login</button>
                    <button class="tab" data-tab="register">Register</button>
                    <button class="tab" data-tab="guest">Guest</button>
                </div>
                <form id="login-form" class="tab-content active" data-tab="login">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
                <form id="register-form" class="tab-content" data-tab="register">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
                <form id="guest-form" class="tab-content" data-tab="guest">
                    <p>Join as guest with random username</p>
                    <button type="submit" class="btn btn-primary">Join as Guest</button>
                </form>
                <div id="auth-error" class="error"></div>
            </div>
            <?php else: ?>
            <div id="chat-container" class="chat-container">
                <div class="chat-sidebar">
                    <div class="chat-user-info">
                        <span class="username"><?= e($chatUser['username']) ?></span>
                        <?php if ($chatUser['is_op'] || isset($_SESSION['admin_id'])): ?><span class="badge-op">OP</span><?php endif; ?>
                        <button id="logout-chat" class="btn-sm">Logout</button>
                    </div>
                    <div class="chat-rooms">
                        <h4>Rooms</h4>
                        <?php foreach ($rooms as $room): ?>
                        <button class="room-btn <?= $room['is_default'] ? 'active' : '' ?>" data-room="<?= $room['id'] ?>" data-name="<?= e($room['name']) ?>" data-topic="<?= e($room['topic']) ?>">
                            <?= e($room['name']) ?>
                        </button>
                        <?php endforeach; ?>
                    </div>
                    <div class="chat-users">
                        <h4>Online Users</h4>
                        <ul id="online-users"></ul>
                    </div>
                </div>
                <div class="chat-main">
                    <div class="chat-header">
                        <span id="current-room"><?= e($defaultRoom['name'] ?? 'Chat') ?></span>
                        <span id="room-topic" class="room-topic"><?= e($defaultRoom['topic'] ?? '') ?></span>
                        <span id="private-indicator" style="display:none">Private: <span id="private-with"></span> <button id="close-private" class="btn-sm">×</button></span>
                    </div>
                    <div id="chat-messages" class="chat-messages"></div>
                    <form id="chat-form" class="chat-input">
                        <input type="text" id="message-input" placeholder="Type a message..." autocomplete="off" required>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                </div>
            </div>
            <?php endif; ?>
    </div>
    <?php include 'includes/footer.php'; ?>
    <script>
    const CHAT_USER = <?= $chatUser ? json_encode(['id'=>$chatUser['id'],'username'=>$chatUser['username'],'is_op'=>$chatUser['is_op']||isset($_SESSION['admin_id'])]) : 'null' ?>;
    const IS_ADMIN = <?= isset($_SESSION['admin_id']) ? 'true' : 'false' ?>;
    const DEFAULT_ROOM = <?= $defaultRoom ? $defaultRoom['id'] : 1 ?>;
    </script>
    <script src="assets/js/chat.js"></script>
</body>
</html>
