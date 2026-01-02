<?php
/**
 * Admin - AzuraCast Media Upload
 */

$stations = getStations();
$currentStation = $stations[0] ?? null;
if (isset($_GET['station'])) {
    foreach ($stations as $s) {
        if ($s['id'] == $_GET['station']) { $currentStation = $s; break; }
    }
}

// Handle upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['audio'])) {
    $file = $_FILES['audio'];
    $playlist = $_POST['playlist'] ?? '';
    $dir = $_POST['directory'] ?? '';
    
    $allowed = ['mp3', 'ogg', 'flac', 'wav', 'aac', 'm4a'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($ext, $allowed)) {
        $error = 'Invalid file type. Allowed: ' . implode(', ', $allowed);
    } elseif ($file['error'] !== UPLOAD_ERR_OK) {
        $error = 'Upload error: ' . $file['error'];
    } else {
        $result = ac_uploadFile($currentStation, $file['tmp_name'], $file['name'], $dir);
        if (isset($result['error'])) {
            $error = $result['error'];
        } else {
            // Add to playlist if selected
            if ($playlist && isset($result['id'])) {
                ac_addToPlaylist($currentStation, $playlist, $result['id']);
            }
            $success = 'File uploaded successfully!';
        }
    }
}
?>

<div class="card">
    <div class="card-header">
        <h3>📁 Upload Music to AzuraCast</h3>
        <select onchange="location.href='admin.php?page=media&station='+this.value" class="form-control" style="width:auto">
            <?php foreach ($stations as $s): ?>
            <option value="<?= $s['id'] ?>" <?= $currentStation && $currentStation['id'] == $s['id'] ? 'selected' : '' ?>><?= e($s['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="card-body">
        <?php if (isset($error)): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
        <?php if (isset($success)): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>
        
        <?php if ($currentStation): ?>
        <form method="post" enctype="multipart/form-data" class="upload-form">
            <div class="form-group">
                <label>Audio File</label>
                <input type="file" name="audio" accept=".mp3,.ogg,.flac,.wav,.aac,.m4a" required class="form-control">
                <small>Supported: MP3, OGG, FLAC, WAV, AAC, M4A</small>
            </div>
            <div class="form-group">
                <label>Directory (optional)</label>
                <input type="text" name="directory" class="form-control" placeholder="e.g., Music/Rock">
            </div>
            <div class="form-group">
                <label>Add to Playlist (optional)</label>
                <select name="playlist" class="form-control" id="playlist-select">
                    <option value="">-- None --</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Upload to AzuraCast</button>
        </form>
        
        <hr>
        <h4>📋 Playlists</h4>
        <div id="playlists-list"></div>
        
        <hr>
        <h4>📂 Media Files</h4>
        <div id="current-path"></div>
        <div id="files-list"></div>
        <?php else: ?>
        <p class="text-muted">No stations configured.</p>
        <?php endif; ?>
    </div>
</div>

<script>
const STATION_ID = <?= $currentStation['id'] ?? 0 ?>;

async function loadPlaylists() {
    const res = await fetch('admin.php?ajax=ac-playlists&station=' + STATION_ID).then(r => r.json());
    if (Array.isArray(res)) {
        document.getElementById('playlist-select').innerHTML = '<option value="">-- None --</option>' + 
            res.map(p => `<option value="${p.id}">${p.name}</option>`).join('');
        document.getElementById('playlists-list').innerHTML = res.map(p => `
            <div class="playlist-row">
                <span class="status-badge ${p.is_enabled ? 'status-active' : 'status-inactive'}">${p.is_enabled ? 'ON' : 'OFF'}</span>
                <strong>${p.name}</strong>
                <span class="text-muted">${p.num_songs || 0} songs</span>
                <button onclick="togglePlaylist(${p.id})" class="btn btn-sm">${p.is_enabled ? 'Disable' : 'Enable'}</button>
            </div>
        `).join('');
    }
}

async function togglePlaylist(id) {
    await fetch('admin.php?ajax=ac-toggle-playlist', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'station=' + STATION_ID + '&playlist_id=' + id
    });
    loadPlaylists();
}

async function loadFiles(path = '') {
    document.getElementById('current-path').innerHTML = path ? `<a href="#" onclick="loadFiles('')">Root</a> / ${path}` : 'Root';
    const res = await fetch('admin.php?ajax=ac-files&station=' + STATION_ID + '&path=' + encodeURIComponent(path)).then(r => r.json());
    if (Array.isArray(res)) {
        document.getElementById('files-list').innerHTML = res.slice(0, 50).map(f => `
            <div class="file-row">
                ${f.is_dir ? `<a href="#" onclick="loadFiles('${f.path}')">📁 ${f.path_short}</a>` : 
                `<span>🎵 ${f.path_short}</span><small class="text-muted">${f.artist || ''} - ${f.title || ''}</small>`}
            </div>
        `).join('') || '<p class="text-muted">No files</p>';
    }
}

loadPlaylists();
loadFiles();
</script>
