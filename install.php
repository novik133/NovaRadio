<?php
/**
 * NovaRadio CMS Installer
 */
session_start();

if (file_exists('config.php')) {
    $config = file_get_contents('config.php');
    if (strpos($config, 'DB_HOST') !== false && strpos($config, "''") === false) {
        header('Location: index.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);
    
    if ($data['action'] === 'check_requirements') {
        $checks = [
            'php_version' => ['name' => 'PHP 8.0+', 'ok' => version_compare(PHP_VERSION, '8.0.0', '>='), 'value' => PHP_VERSION],
            'pdo' => ['name' => 'PDO Extension', 'ok' => extension_loaded('pdo'), 'value' => extension_loaded('pdo') ? 'Loaded' : 'Missing'],
            'pdo_mysql' => ['name' => 'PDO MySQL', 'ok' => extension_loaded('pdo_mysql'), 'value' => extension_loaded('pdo_mysql') ? 'Loaded' : 'Missing'],
            'curl' => ['name' => 'cURL Extension', 'ok' => extension_loaded('curl'), 'value' => extension_loaded('curl') ? 'Loaded' : 'Missing'],
            'json' => ['name' => 'JSON Extension', 'ok' => extension_loaded('json'), 'value' => extension_loaded('json') ? 'Loaded' : 'Missing'],
            'session' => ['name' => 'Session Support', 'ok' => extension_loaded('session'), 'value' => extension_loaded('session') ? 'Loaded' : 'Missing'],
            'mbstring' => ['name' => 'Mbstring Extension', 'ok' => extension_loaded('mbstring'), 'value' => extension_loaded('mbstring') ? 'Loaded' : 'Optional'],
            'schema' => ['name' => 'Schema File', 'ok' => file_exists('schema.sql'), 'value' => file_exists('schema.sql') ? 'Found' : 'Missing'],
            'uploads_writable' => ['name' => 'Uploads Writable', 'ok' => is_writable('.') || @mkdir('uploads', 0755), 'value' => is_writable('.') ? 'Writable' : 'Check permissions'],
            'config_writable' => ['name' => 'Config Writable', 'ok' => is_writable('.') || !file_exists('config.php'), 'value' => is_writable('.') ? 'Writable' : 'Check permissions'],
        ];
        $allOk = true;
        foreach (['php_version', 'pdo', 'pdo_mysql', 'curl', 'json', 'session', 'schema', 'config_writable'] as $req) {
            if (!$checks[$req]['ok']) $allOk = false;
        }
        echo json_encode(['checks' => $checks, 'ok' => $allOk]);
        exit;
    }
    
    if ($data['action'] === 'test_db') {
        try {
            $pdo = new PDO("mysql:host={$data['host']};charset=utf8mb4", $data['user'], $data['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$data['name']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
    
    if ($data['action'] === 'install') {
        try {
            $config = "<?php\ndefine('DB_HOST', '{$data['db_host']}');\ndefine('DB_NAME', '{$data['db_name']}');\ndefine('DB_USER', '{$data['db_user']}');\ndefine('DB_PASS', '{$data['db_pass']}');\ndefine('SITE_NAME', '{$data['site_name']}');\ndefine('SITE_URL', '{$data['site_url']}');\ndefine('INSTALLED', true);\n";
            file_put_contents('config.php', $config);
            $pdo = new PDO("mysql:host={$data['db_host']};dbname={$data['db_name']};charset=utf8mb4", $data['db_user'], $data['db_pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => true]);
            $schema = file_get_contents('schema.sql');
            $pdo->exec($schema);
            $pdo->prepare("INSERT INTO admins (username, email, password, role) VALUES (?, ?, ?, 'super_admin')")->execute([$data['admin_user'], $data['admin_email'] ?? '', password_hash($data['admin_pass'], PASSWORD_ARGON2ID)]);
            $pdo->prepare("INSERT INTO stations (name, slug, genre, azuracast_url, api_key, station_id, stream_url, is_default, active) VALUES (?, ?, ?, ?, ?, ?, ?, 1, 1)")->execute([$data['station_name'] ?? 'Main Station', $data['station_slug'] ?? 'main', $data['station_genre'] ?? 'Electronic', $data['azuracast_url'] ?? '', $data['azuracast_key'] ?? '', $data['azuracast_station_id'] ?? 1, $data['stream_url'] ?? '']);
            $pdo->prepare("UPDATE settings SET value = ? WHERE `key` = 'site_name'")->execute([$data['site_name']]);
            $pdo->prepare("UPDATE settings SET value = ? WHERE `key` = 'site_url'")->execute([$data['site_url']]);
            @mkdir('uploads', 0755, true);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
}
