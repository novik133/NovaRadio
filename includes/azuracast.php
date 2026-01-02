<?php
/**
 * AzuraCast API Helper Functions
 */

function azuracastAPI($station, $endpoint, $method = 'GET', $data = null) {
    if (!$station || !$station['azuracast_url']) return ['error' => 'No AzuraCast configured'];
    
    $url = rtrim($station['azuracast_url'], '/') . '/api' . $endpoint;
    $ch = curl_init($url);
    
    $headers = ['Content-Type: application/json'];
    if ($station['api_key']) {
        $headers[] = "X-API-Key: {$station['api_key']}";
    }
    
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    } elseif ($method === 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        if ($data) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    } elseif ($method === 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) return ['error' => $error];
    if ($httpCode >= 400) return ['error' => "HTTP $httpCode", 'response' => json_decode($response, true)];
    
    return json_decode($response, true) ?: [];
}

function azuracastUpload($station, $endpoint, $filePath, $fileName) {
    if (!$station || !$station['azuracast_url']) return ['error' => 'No AzuraCast configured'];
    
    $url = rtrim($station['azuracast_url'], '/') . '/api' . $endpoint;
    $ch = curl_init($url);
    
    $cfile = new CURLFile($filePath, mime_content_type($filePath), $fileName);
    
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ["X-API-Key: {$station['api_key']}"],
        CURLOPT_POSTFIELDS => ['file' => $cfile],
        CURLOPT_TIMEOUT => 300,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return json_decode($response, true) ?: ['error' => "HTTP $httpCode"];
}

// Station endpoints
function ac_getNowPlaying($station) {
    return azuracastAPI($station, "/nowplaying/{$station['station_id']}");
}

function ac_getStationStatus($station) {
    return azuracastAPI($station, "/station/{$station['station_id']}");
}

function ac_getHistory($station, $limit = 20) {
    return azuracastAPI($station, "/station/{$station['station_id']}/history?limit=$limit");
}

function ac_getListeners($station) {
    return azuracastAPI($station, "/station/{$station['station_id']}/listeners");
}

function ac_getSchedule($station) {
    return azuracastAPI($station, "/station/{$station['station_id']}/schedule");
}

// Media/Files
function ac_getFiles($station, $path = '') {
    $endpoint = "/station/{$station['station_id']}/files/list";
    if ($path) $endpoint .= "?currentDirectory=" . urlencode($path);
    return azuracastAPI($station, $endpoint);
}

function ac_uploadFile($station, $filePath, $fileName, $directory = '') {
    $endpoint = "/station/{$station['station_id']}/files";
    if ($directory) $endpoint .= "?path=" . urlencode($directory);
    return azuracastUpload($station, $endpoint, $filePath, $fileName);
}

function ac_deleteFile($station, $fileId) {
    return azuracastAPI($station, "/station/{$station['station_id']}/file/$fileId", 'DELETE');
}

function ac_getFileInfo($station, $fileId) {
    return azuracastAPI($station, "/station/{$station['station_id']}/file/$fileId");
}

// Playlists
function ac_getPlaylists($station) {
    return azuracastAPI($station, "/station/{$station['station_id']}/playlists");
}

function ac_getPlaylist($station, $playlistId) {
    return azuracastAPI($station, "/station/{$station['station_id']}/playlist/$playlistId");
}

function ac_createPlaylist($station, $data) {
    return azuracastAPI($station, "/station/{$station['station_id']}/playlists", 'POST', $data);
}

function ac_updatePlaylist($station, $playlistId, $data) {
    return azuracastAPI($station, "/station/{$station['station_id']}/playlist/$playlistId", 'PUT', $data);
}

function ac_deletePlaylist($station, $playlistId) {
    return azuracastAPI($station, "/station/{$station['station_id']}/playlist/$playlistId", 'DELETE');
}

function ac_addToPlaylist($station, $playlistId, $mediaId) {
    return azuracastAPI($station, "/station/{$station['station_id']}/playlist/$playlistId/import", 'POST', ['media_ids' => [$mediaId]]);
}

function ac_togglePlaylist($station, $playlistId) {
    return azuracastAPI($station, "/station/{$station['station_id']}/playlist/$playlistId/toggle", 'PUT');
}

function ac_getPlaylistQueue($station, $playlistId) {
    return azuracastAPI($station, "/station/{$station['station_id']}/playlist/$playlistId/queue");
}

// Queue
function ac_getQueue($station) {
    return azuracastAPI($station, "/station/{$station['station_id']}/queue");
}

function ac_clearQueue($station) {
    return azuracastAPI($station, "/station/{$station['station_id']}/queue", 'DELETE');
}

// Requests
function ac_getRequests($station) {
    return azuracastAPI($station, "/station/{$station['station_id']}/requests");
}

function ac_submitRequest($station, $mediaId) {
    return azuracastAPI($station, "/station/{$station['station_id']}/request/$mediaId", 'POST');
}

// Streaming/Live
function ac_getStreamers($station) {
    return azuracastAPI($station, "/station/{$station['station_id']}/streamers");
}

function ac_getStreamer($station, $streamerId) {
    return azuracastAPI($station, "/station/{$station['station_id']}/streamer/$streamerId");
}

function ac_createStreamer($station, $data) {
    return azuracastAPI($station, "/station/{$station['station_id']}/streamers", 'POST', $data);
}

function ac_updateStreamer($station, $streamerId, $data) {
    return azuracastAPI($station, "/station/{$station['station_id']}/streamer/$streamerId", 'PUT', $data);
}

function ac_deleteStreamer($station, $streamerId) {
    return azuracastAPI($station, "/station/{$station['station_id']}/streamer/$streamerId", 'DELETE');
}

// Backend actions
function ac_restart($station) {
    return azuracastAPI($station, "/station/{$station['station_id']}/restart", 'POST');
}

function ac_skipSong($station) {
    return azuracastAPI($station, "/station/{$station['station_id']}/backend/skip", 'POST');
}

function ac_disconnectStreamer($station) {
    return azuracastAPI($station, "/station/{$station['station_id']}/backend/disconnect", 'POST');
}

// Reports
function ac_getListenerReport($station, $start = null, $end = null) {
    $params = [];
    if ($start) $params['start'] = $start;
    if ($end) $params['end'] = $end;
    $query = $params ? '?' . http_build_query($params) : '';
    return azuracastAPI($station, "/station/{$station['station_id']}/reports/listeners$query");
}

// Mounts
function ac_getMounts($station) {
    return azuracastAPI($station, "/station/{$station['station_id']}/mounts");
}

// HLS Streams
function ac_getHlsStreams($station) {
    return azuracastAPI($station, "/station/{$station['station_id']}/hls_streams");
}

// SFTP Users
function ac_getSftpUsers($station) {
    return azuracastAPI($station, "/station/{$station['station_id']}/sftp-users");
}

// Webhooks
function ac_getWebhooks($station) {
    return azuracastAPI($station, "/station/{$station['station_id']}/webhooks");
}
