<?php
/**
 * DJ Panel - AzuraCast Integration
 */
session_start();
require_once 'config.php';
require_once 'includes/functions.php';
require_once 'includes/azuracast.php';

$page = $_GET['page'] ?? 'dashboard';

// Login
if ($page === 'login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $dj = fetch("SELECT * FROM djs WHERE email = ? AND active = 1", [$email]);
        if ($dj && password_verify($password, $dj['password'])) {
            $_SESSION['dj_id'] = $dj['id'];
            query("UPDATE djs SET last_login = NOW() WHERE id = ?", [$dj['id']]);
            
            // Auto-create/link chat user as OP
            $chatUser = fetch("SELECT * FROM chat_users WHERE dj_id = ?", [$dj['id']]);
            if (!$chatUser) {
                $chatUser = fetch("SELECT * FROM chat_users WHERE email = ?", [$dj['email']]);
                if ($chatUser) {
                    query("UPDATE chat_users SET dj_id = ?, is_op = 1 WHERE id = ?", [$dj['id'], $chatUser['id']]);
                } else {
                    $chatId = insert('chat_users', ['username' => $dj['name'], 'email' => $dj['email'], 'password' => $dj['password'], 'dj_id' => $dj['id'], 'is_op' => 1]);
                    $chatUser = ['id' => $chatId, 'username' => $dj['name'], 'is_op' => 1];
                }
            } else {
                query("UPDATE chat_users SET is_op = 1, last_seen = NOW() WHERE id = ?", [$chatUser['id']]);
            }
            $_SESSION['chat_user'] = ['id' => $chatUser['id'], 'username' => $chatUser['username'] ?? $dj['name'], 'is_op' => 1];
            
            header('Location: dj.php');
            exit;
        }
        $error = 'Invalid credentials';
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>DJ Login - <?= e(SITE_NAME) ?></title>
        <link rel="stylesheet" href="assets/css/admin.css">
    </head>
    <body class="login-page">
        <div class="login-box">
            <h1>🎧 DJ Panel</h1>
            <form method="post">
                <?php if (isset($error)): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
                <div class="form-group"><input type="email" name="email" placeholder="Email" required></div>
                <div class="form-group"><input type="password" name="password" placeholder="Password" required></div>
                <button type="submit" class="btn btn-primary" style="width:100%">Login</button>
            </form>
            <p style="text-align:center;margin-top:1rem"><a href="index.php">← Back to site</a></p>
        </div>
    </body>
    </html>
    <?php
    exit;
}

if ($page === 'logout') {
    unset($_SESSION['dj_id']);
    header('Location: dj.php?page=login');
    exit;
}

// Require DJ login
if (!isset($_SESSION['dj_id'])) {
    header('Location: dj.php?page=login');
    exit;
}

$dj = fetch("SELECT * FROM djs WHERE id = ? AND active = 1", [$_SESSION['dj_id']]);
if (!$dj) {
    unset($_SESSION['dj_id']);
    header('Location: dj.php?page=login');
    exit;
}

// Get DJ's assigned stations (from schedule)
$djStations = fetchAll("SELECT DISTINCT s.* FROM stations s 
    INNER JOIN schedule sc ON sc.station_id = s.id 
    WHERE sc.dj_id = ? AND s.active = 1", [$dj['id']]);

// If no assigned stations, get default
if (empty($djStations)) {
    $djStations = [getDefaultStation()];
}

$currentStation = $djStations[0];
if (isset($_GET['station'])) {
    foreach ($djStations as $st) {
        if ($st['id'] == $_GET['station']) {
            $currentStation = $st;
            break;
        }
    }
}

// AJAX handlers
if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    
    switch ($_GET['ajax']) {
        case 'nowplaying':
            echo json_encode(ac_getNowPlaying($currentStation));
            break;
            
        case 'listeners':
            echo json_encode(ac_getListeners($currentStation));
            break;
            
        case 'history':
            echo json_encode(ac_getHistory($currentStation, 20));
            break;
            
        case 'queue':
            echo json_encode(ac_getQueue($currentStation));
            break;
            
        case 'skip':
            if ($dj['can_stream']) echo json_encode(ac_skipSong($currentStation));
            else echo json_encode(['error' => 'Permission denied']);
            break;
            
        case 'local-requests':
            $requests = fetchAll("SELECT * FROM requests WHERE station_id = ? ORDER BY FIELD(status,'pending','approved','played','rejected'), created_at DESC LIMIT 50", [$currentStation['id']]);
            echo json_encode($requests);
            break;
            
        case 'update-request':
            $id = (int)($_POST['id'] ?? 0);
            $status = $_POST['status'] ?? '';
            if (in_array($status, ['approved','played','rejected'])) {
                update('requests', ['status' => $status], 'id = ?', [$id]);
                echo json_encode(['success' => true]);
            } else echo json_encode(['error' => 'Invalid status']);
            break;
            
        case 'files':
            if ($dj['can_upload']) {
                $path = $_GET['path'] ?? '';
                echo json_encode(ac_getFiles($currentStation, $path));
            } else echo json_encode(['error' => 'Permission denied']);
            break;
            
        case 'upload':
            if (!$dj['can_upload']) { echo json_encode(['error' => 'Permission denied']); break; }
            if (isset($_FILES['file'])) {
                $dir = $_POST['directory'] ?? '';
                $result = ac_uploadFile($currentStation, $_FILES['file']['tmp_name'], $_FILES['file']['name'], $dir);
                echo json_encode($result);
            } else echo json_encode(['error' => 'No file']);
            break;
            
        case 'delete-file':
            if (!$dj['can_upload']) { echo json_encode(['error' => 'Permission denied']); break; }
            $fileId = $_POST['file_id'] ?? '';
            echo json_encode(ac_deleteFile($currentStation, $fileId));
            break;
            
        case 'playlists':
            if ($dj['can_playlist']) echo json_encode(ac_getPlaylists($currentStation));
            else echo json_encode(['error' => 'Permission denied']);
            break;
            
        case 'playlist':
            if ($dj['can_playlist']) {
                $id = $_GET['id'] ?? '';
                echo json_encode(ac_getPlaylist($currentStation, $id));
            } else echo json_encode(['error' => 'Permission denied']);
            break;
            
        case 'toggle-playlist':
            if (!$dj['can_playlist']) { echo json_encode(['error' => 'Permission denied']); break; }
            $id = $_POST['playlist_id'] ?? '';
            echo json_encode(ac_togglePlaylist($currentStation, $id));
            break;
            
        case 'create-playlist':
            if (!$dj['can_playlist']) { echo json_encode(['error' => 'Permission denied']); break; }
            $data = json_decode(file_get_contents('php://input'), true);
            echo json_encode(ac_createPlaylist($currentStation, $data));
            break;
            
        case 'requests':
            echo json_encode(ac_getRequests($currentStation));
            break;
            
        case 'streamer-info':
            if ($dj['azuracast_dj_id']) {
                echo json_encode(ac_getStreamer($currentStation, $dj['azuracast_dj_id']));
            } else echo json_encode(['error' => 'No streamer account linked']);
            break;
            
        case 'disconnect':
            if ($dj['can_stream']) echo json_encode(ac_disconnectStreamer($currentStation));
            else echo json_encode(['error' => 'Permission denied']);
            break;
            
        case 'clear-queue':
            if ($dj['can_playlist']) echo json_encode(ac_clearQueue($currentStation));
            else echo json_encode(['error' => 'Permission denied']);
            break;
            
        case 'restart':
            if ($dj['can_stream']) echo json_encode(ac_restart($currentStation));
            else echo json_encode(['error' => 'Permission denied']);
            break;
            
        case 'mounts':
            echo json_encode(ac_getMounts($currentStation));
            break;
            
        case 'schedule':
            echo json_encode(ac_getSchedule($currentStation));
            break;
            
        case 'report':
            $start = $_GET['start'] ?? date('Y-m-d', strtotime('-7 days'));
            $end = $_GET['end'] ?? date('Y-m-d');
            echo json_encode(ac_getListenerReport($currentStation, $start, $end));
            break;
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DJ Panel - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/djpanel.css">
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <span>🎧 DJ Panel</span>
        </div>
        <div class="dj-info">
            <img src="<?= e($dj['image'] ?: 'assets/img/dj-placeholder.png') ?>" class="dj-avatar">
            <span><?= e($dj['name']) ?></span>
        </div>
        <?php if (count($djStations) > 1): ?>
        <select class="station-select" onchange="location.href='dj.php?station='+this.value">
            <?php foreach ($djStations as $st): ?>
            <option value="<?= $st['id'] ?>" <?= $currentStation['id'] == $st['id'] ? 'selected' : '' ?>><?= e($st['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <?php endif; ?>
        <nav class="sidebar-nav">
            <a href="dj.php?page=dashboard" class="<?= $page === 'dashboard' ? 'active' : '' ?>">📊 Dashboard</a>
            <a href="dj.php?page=nowplaying" class="<?= $page === 'nowplaying' ? 'active' : '' ?>">🎵 Now Playing</a>
            <?php if ($dj['can_stream']): ?>
            <a href="dj.php?page=streaming" class="<?= $page === 'streaming' ? 'active' : '' ?>">🎙️ Go Live</a>
            <?php endif; ?>
            <?php if ($dj['can_playlist']): ?>
            <a href="dj.php?page=playlists" class="<?= $page === 'playlists' ? 'active' : '' ?>">📋 Playlists</a>
            <?php endif; ?>
            <?php if ($dj['can_upload']): ?>
            <a href="dj.php?page=media" class="<?= $page === 'media' ? 'active' : '' ?>">📁 Media</a>
            <?php endif; ?>
            <a href="dj.php?page=queue" class="<?= $page === 'queue' ? 'active' : '' ?>">📜 Queue</a>
            <a href="dj.php?page=requests" class="<?= $page === 'requests' ? 'active' : '' ?>">🎶 Requests</a>
            <a href="dj.php?page=schedule" class="<?= $page === 'schedule' ? 'active' : '' ?>">📅 Schedule</a>
            <a href="dj.php?page=reports" class="<?= $page === 'reports' ? 'active' : '' ?>">📈 Reports</a>
            <a href="dj.php?page=profile" class="<?= $page === 'profile' ? 'active' : '' ?>">👤 Profile</a>
        </nav>
        <div class="sidebar-footer">
            <a href="index.php" target="_blank">View Site</a>
            <a href="dj.php?page=logout">Logout</a>
        </div>
    </aside>
    
    <main class="admin-main">
        <header class="admin-header">
            <h1><?= ucfirst($page) ?></h1>
            <div class="header-status">
                <span id="live-status" class="status-badge">Checking...</span>
                <span id="listener-count">0 listeners</span>
            </div>
        </header>
        
        <div class="admin-content">
            <?php include "dj/{$page}.php"; ?>
        </div>
    </main>
    
    <script>
    const STATION_ID = <?= $currentStation['id'] ?? 0 ?>;
    const DJ_ID = <?= $dj['id'] ?>;
    const CAN_STREAM = <?= $dj['can_stream'] ? 'true' : 'false' ?>;
    const CAN_UPLOAD = <?= $dj['can_upload'] ? 'true' : 'false' ?>;
    const CAN_PLAYLIST = <?= $dj['can_playlist'] ? 'true' : 'false' ?>;
    </script>
    <script src="assets/js/djpanel.js"></script>
</body>
</html>
