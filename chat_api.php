<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$chatUser = $_SESSION['chat_user'] ?? null;
$isAdmin = isset($_SESSION['admin_id']);

if (getSetting('chat_enabled', '1') !== '1' && !$isAdmin) {
    die(json_encode(['error' => 'Chat disabled']));
}

switch ($action) {
    case 'register':
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        if (strlen($username) < 3 || strlen($password) < 4) die(json_encode(['error' => 'Invalid input']));
        if (fetch("SELECT id FROM chat_users WHERE username = ? OR email = ?", [$username, $email])) die(json_encode(['error' => 'Username or email taken']));
        $id = insert('chat_users', ['username' => $username, 'email' => $email, 'password' => password_hash($password, PASSWORD_DEFAULT)]);
        $_SESSION['chat_user'] = ['id' => $id, 'username' => $username, 'is_op' => 0];
        echo json_encode(['success' => true]);
        break;

    case 'login':
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $user = fetch("SELECT * FROM chat_users WHERE username = ? AND is_guest = 0", [$username]);
        if (!$user || !password_verify($password, $user['password'])) die(json_encode(['error' => 'Invalid credentials']));
        if ($user['is_banned']) die(json_encode(['error' => 'You are banned']));
        query("UPDATE chat_users SET last_seen = NOW() WHERE id = ?", [$user['id']]);
        $_SESSION['chat_user'] = ['id' => $user['id'], 'username' => $user['username'], 'is_op' => $user['is_op']];
        echo json_encode(['success' => true]);
        break;

    case 'guest':
        $username = 'Guest_' . substr(md5(uniqid()), 0, 6);
        $id = insert('chat_users', ['username' => $username, 'is_guest' => 1]);
        $_SESSION['chat_user'] = ['id' => $id, 'username' => $username, 'is_op' => 0];
        echo json_encode(['success' => true, 'username' => $username]);
        break;

    case 'logout':
        unset($_SESSION['chat_user']);
        echo json_encode(['success' => true]);
        break;

    case 'send':
        if (!$chatUser) die(json_encode(['error' => 'Not logged in']));
        $message = trim($_POST['message'] ?? '');
        $roomId = (int)($_POST['room_id'] ?? 1);
        if (!$message) die(json_encode(['error' => 'Empty message']));
        if (!fetch("SELECT id FROM chat_rooms WHERE id = ? AND active = 1", [$roomId])) die(json_encode(['error' => 'Invalid room']));
        insert('chat_messages', ['user_id' => $chatUser['id'], 'room_id' => $roomId, 'message' => $message]);
        query("UPDATE chat_users SET last_seen = NOW() WHERE id = ?", [$chatUser['id']]);
        echo json_encode(['success' => true]);
        break;

    case 'send_private':
        if (!$chatUser) die(json_encode(['error' => 'Not logged in']));
        $toUser = (int)($_POST['to_user'] ?? 0);
        $message = trim($_POST['message'] ?? '');
        if (!$message || !$toUser) die(json_encode(['error' => 'Invalid']));
        insert('chat_private', ['from_user' => $chatUser['id'], 'to_user' => $toUser, 'message' => $message]);
        echo json_encode(['success' => true]);
        break;

    case 'messages':
        if (!$chatUser) die(json_encode(['error' => 'Not logged in']));
        $roomId = (int)($_GET['room_id'] ?? 1);
        $lastId = (int)($_GET['last_id'] ?? 0);
        $messages = fetchAll("SELECT m.*, u.username, u.is_op FROM chat_messages m JOIN chat_users u ON m.user_id = u.id WHERE m.room_id = ? AND m.id > ? ORDER BY m.id DESC LIMIT 50", [$roomId, $lastId]);
        query("UPDATE chat_users SET last_seen = NOW() WHERE id = ?", [$chatUser['id']]);
        echo json_encode(array_reverse($messages));
        break;

    case 'private_messages':
        if (!$chatUser) die(json_encode(['error' => 'Not logged in']));
        $withUser = (int)($_GET['with_user'] ?? 0);
        $lastId = (int)($_GET['last_id'] ?? 0);
        $messages = fetchAll("SELECT p.*, u.username FROM chat_private p JOIN chat_users u ON p.from_user = u.id WHERE p.id > ? AND ((p.from_user = ? AND p.to_user = ?) OR (p.from_user = ? AND p.to_user = ?)) ORDER BY p.id DESC LIMIT 50", [$lastId, $chatUser['id'], $withUser, $withUser, $chatUser['id']]);
        query("UPDATE chat_private SET is_read = 1 WHERE to_user = ? AND from_user = ?", [$chatUser['id'], $withUser]);
        echo json_encode(array_reverse($messages));
        break;

    case 'online':
        $users = fetchAll("SELECT id, username, is_op FROM chat_users WHERE last_seen > DATE_SUB(NOW(), INTERVAL 2 MINUTE) AND is_banned = 0 ORDER BY username");
        echo json_encode($users);
        break;

    case 'rooms':
        $rooms = fetchAll("SELECT id, name, slug, topic FROM chat_rooms WHERE active = 1 ORDER BY sort_order, name");
        echo json_encode($rooms);
        break;

    case 'op':
        if (!$isAdmin) die(json_encode(['error' => 'Unauthorized']));
        $userId = (int)($_POST['user_id'] ?? 0);
        $op = (int)($_POST['op'] ?? 0);
        update('chat_users', ['is_op' => $op], 'id = ?', [$userId]);
        echo json_encode(['success' => true]);
        break;

    case 'ban':
        if (!$isAdmin && !($chatUser && $chatUser['is_op'])) die(json_encode(['error' => 'Unauthorized']));
        $userId = (int)($_POST['user_id'] ?? 0);
        $ban = (int)($_POST['ban'] ?? 1);
        update('chat_users', ['is_banned' => $ban], 'id = ?', [$userId]);
        echo json_encode(['success' => true]);
        break;

    case 'delete':
        if (!$isAdmin && !($chatUser && $chatUser['is_op'])) die(json_encode(['error' => 'Unauthorized']));
        $msgId = (int)($_POST['msg_id'] ?? 0);
        delete('chat_messages', 'id = ?', [$msgId]);
        echo json_encode(['success' => true]);
        break;

    default:
        echo json_encode(['error' => 'Invalid action']);
}
