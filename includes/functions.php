<?php
/**
 * Database & Helper Functions
 */

$pdo = null;

function db() {
    global $pdo;
    if (!$pdo) {
        $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4", DB_USER, DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    }
    return $pdo;
}

function query($sql, $params = []) { $stmt = db()->prepare($sql); $stmt->execute($params); return $stmt; }
function fetch($sql, $params = []) { return query($sql, $params)->fetch() ?: null; }
function fetchAll($sql, $params = []) { return query($sql, $params)->fetchAll(); }
function insert($table, $data) { $cols = implode(',', array_keys($data)); $ph = implode(',', array_fill(0, count($data), '?')); query("INSERT INTO $table ($cols) VALUES ($ph)", array_values($data)); return db()->lastInsertId(); }
function update($table, $data, $where, $params = []) { $set = implode(',', array_map(fn($k) => "$k=?", array_keys($data))); return query("UPDATE $table SET $set WHERE $where", [...array_values($data), ...$params])->rowCount(); }
function delete($table, $where, $params = []) { return query("DELETE FROM $table WHERE $where", $params)->rowCount(); }

function e($str) { return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8'); }
function redirect($url) { header("Location: $url"); exit; }
function isLoggedIn() { return isset($_SESSION['admin_id']); }
function requireLogin() { if (!isLoggedIn()) redirect('admin.php?page=login'); }

// Settings
function getSetting($key, $default = '') {
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        foreach (fetchAll("SELECT `key`, value FROM settings") as $s) $cache[$s['key']] = $s['value'];
    }
    return $cache[$key] ?? $default;
}

function setSetting($key, $value, $group = 'general') {
    $exists = fetch("SELECT id FROM settings WHERE `key` = ?", [$key]);
    if ($exists) update('settings', ['value' => $value], '`key` = ?', [$key]);
    else insert('settings', ['key' => $key, 'value' => $value, 'group' => $group]);
}

// Stations
function getStations($activeOnly = true) {
    $sql = "SELECT * FROM stations" . ($activeOnly ? " WHERE active = 1" : "") . " ORDER BY sort_order, name";
    return fetchAll($sql);
}

function getStation($id) { return fetch("SELECT * FROM stations WHERE id = ?", [$id]); }
function getStationBySlug($slug) { return fetch("SELECT * FROM stations WHERE slug = ? AND active = 1", [$slug]); }
function getDefaultStation() { return fetch("SELECT * FROM stations WHERE is_default = 1 AND active = 1") ?: fetch("SELECT * FROM stations WHERE active = 1 ORDER BY id LIMIT 1"); }

// AzuraCast API
function azuracastRequest($station, $endpoint) {
    if (!$station || !$station['azuracast_url']) return null;
    $ch = curl_init(rtrim($station['azuracast_url'], '/') . $endpoint);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $station['api_key'] ? ["X-API-Key: {$station['api_key']}"] : [],
        CURLOPT_TIMEOUT => 10,
    ]);
    $response = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ($code === 200) ? json_decode($response, true) : null;
}

function getNowPlaying($station) {
    return azuracastRequest($station, "/api/nowplaying/{$station['station_id']}");
}

function getHistory($station, $limit = 10) {
    return azuracastRequest($station, "/api/station/{$station['station_id']}/history?limit=$limit") ?? [];
}

// Menu & Widgets
function getMenuItems($location = 'header') {
    return fetchAll("SELECT * FROM menu_items WHERE location = ? AND active = 1 ORDER BY sort_order", [$location]);
}

function getWidgets($location) {
    return fetchAll("SELECT * FROM widgets WHERE location = ? AND active = 1 ORDER BY sort_order", [$location]);
}

// Analytics
function trackPageView($page) {
    try {
        $today = date('Y-m-d');
        $exists = fetch("SELECT id FROM analytics WHERE page = ? AND date = ?", [$page, $today]);
        if ($exists) query("UPDATE analytics SET views = views + 1 WHERE id = ?", [$exists['id']]);
        else insert('analytics', ['page' => $page, 'views' => 1, 'date' => $today]);
    } catch (Exception $e) {}
}

// Template helpers
function siteName() { return getSetting('site_name', SITE_NAME); }
function logoUrl() { return getSetting('logo_url'); }
function logoText() { return getSetting('logo_text', siteName()); }
function copyright() {
    $text = getSetting('copyright_text', '© {year} {site_name}');
    return str_replace(['{year}', '{site_name}'], [date('Y'), siteName()], $text);
}


// Polls
function getActivePoll() {
    return fetch("SELECT * FROM polls WHERE active = 1 AND (ends_at IS NULL OR ends_at > NOW()) ORDER BY created_at DESC LIMIT 1");
}

function getPollOptions($pollId) {
    return fetchAll("SELECT * FROM poll_options WHERE poll_id = ? ORDER BY id", [$pollId]);
}

function hasVoted($pollId) {
    $ip = $_SERVER['REMOTE_ADDR'];
    return fetch("SELECT id FROM poll_votes WHERE poll_id = ? AND ip_address = ?", [$pollId, $ip]) !== null;
}

// Ads
function getAd($position) {
    return fetch("SELECT * FROM ads WHERE position = ? AND active = 1 AND (starts_at IS NULL OR starts_at <= NOW()) AND (ends_at IS NULL OR ends_at > NOW()) ORDER BY RAND() LIMIT 1", [$position]);
}

function renderAd($position) {
    $ad = getAd($position);
    if (!$ad) return '';
    $html = '<div class="ad-banner" data-ad="' . $ad['id'] . '">';
    if ($ad['content']) $html .= $ad['content'];
    elseif ($ad['image']) $html .= '<a href="' . e($ad['link']) . '" target="_blank" onclick="trackAdClick(' . $ad['id'] . ')"><img src="' . e($ad['image']) . '" alt=""></a>';
    $html .= '</div>';
    return $html;
}

// Posts
function getRecentPosts($limit = 5) {
    return fetchAll("SELECT * FROM posts WHERE active = 1 AND (published_at IS NULL OR published_at <= NOW()) ORDER BY published_at DESC LIMIT ?", [$limit]);
}

// Maintenance mode check
function checkMaintenance() {
    if (getSetting('maintenance_mode', '0') === '1' && !isset($_SESSION['admin_id'])) {
        echo '<!DOCTYPE html><html><head><title>Maintenance</title><style>body{font-family:sans-serif;display:flex;justify-content:center;align-items:center;height:100vh;margin:0;background:#0f0f1a;color:#fff;text-align:center;}</style></head><body><div><h1>🔧</h1><h2>Under Maintenance</h2><p>' . e(getSetting('maintenance_message')) . '</p></div></body></html>';
        exit;
    }
}
