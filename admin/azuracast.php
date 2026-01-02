<?php
/**
 * Admin - Full AzuraCast Control
 */

$stations = getStations();
$currentStation = $stations[0] ?? null;
if (isset($_GET['station'])) {
    foreach ($stations as $s) {
        if ($s['id'] == $_GET['station']) { $currentStation = $s; break; }
    }
}
?>

<div class="page-header">
    <h2>🎛️ AzuraCast Control</h2>
    <select onchange="location.href='admin.php?page=azuracast&station='+this.value" class="form-control" style="width:auto">
        <?php foreach ($stations as $s): ?>
        <option value="<?= $s['id'] ?>" <?= $currentStation && $currentStation['id'] == $s['id'] ? 'selected' : '' ?>><?= e($s['name']) ?></option>
        <?php endforeach; ?>
    </select>
</div>

<?php if (!$currentStation): ?>
<div class="alert alert-warning">No stations configured. Add a station first.</div>
<?php else: ?>

<div class="row-3">
    <div class="card">
        <div class="card-header"><h3>🎵 Now Playing</h3></div>
        <div class="card-body">
            <div id="np-display">Loading...</div>
            <div class="btn-group" style="margin-top:1rem">
                <button onclick="skipTrack()" class="btn btn-warning">⏭️ Skip</button>
                <button onclick="disconnectStreamer()" class="btn btn-danger">🔌 Disconnect DJ</button>
                <button onclick="restartStation()" class="btn btn-secondary">🔄 Restart</button>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3>👥 Listeners</h3></div>
        <div class="card-body"><div id="listeners-count">0</div><small>Current listeners</small></div>
    </div>
    <div class="card">
        <div class="card-header"><h3>📡 Status</h3></div>
        <div class="card-body"><div id="station-status">Checking...</div></div>
    </div>
</div>

<div class="row-2">
    <div class="card">
        <div class="card-header">
            <h3>📋 Playlists</h3>
            <button onclick="showCreatePlaylist()" class="btn btn-sm btn-primary">+ New</button>
        </div>
        <div class="card-body"><div id="playlists-list"></div></div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3>🎙️ Streamers/DJs</h3>
            <button onclick="showCreateStreamer()" class="btn btn-sm btn-primary">+ New</button>
        </div>
        <div class="card-body"><div id="streamers-list"></div></div>
    </div>
</div>

<div class="row-2">
    <div class="card">
        <div class="card-header"><h3>📜 Queue</h3><button onclick="clearQueue()" class="btn btn-sm btn-danger">Clear</button></div>
        <div class="card-body"><div id="queue-list" style="max-height:300px;overflow-y:auto"></div></div>
    </div>
    <div class="card">
        <div class="card-header"><h3>📂 Media Files</h3></div>
        <div class="card-body">
            <div id="media-path"></div>
            <div id="media-list" style="max-height:300px;overflow-y:auto"></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3>📡 Mounts & HLS</h3></div>
    <div class="card-body"><div id="mounts-list"></div></div>
</div>

<!-- Create Playlist Modal -->
<div id="playlist-modal" class="modal" style="display:none">
    <div class="modal-content">
        <div class="modal-header"><h3>Create Playlist</h3><button onclick="closeModal('playlist-modal')" class="btn-close">×</button></div>
        <form id="playlist-form">
            <div class="form-group"><label>Name</label><input type="text" name="name" required></div>
            <div class="form-group"><label>Type</label>
                <select name="type"><option value="default">General Rotation</option><option value="once_per_hour">Once per Hour</option><option value="once_per_x_songs">Once per X Songs</option><option value="once_per_x_minutes">Once per X Minutes</option></select>
            </div>
            <div class="form-group"><label>Weight (1-25)</label><input type="number" name="weight" value="3" min="1" max="25"></div>
            <label class="checkbox"><input type="checkbox" name="is_enabled" checked> Enabled</label>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
</div>

<!-- Create Streamer Modal -->
<div id="streamer-modal" class="modal" style="display:none">
    <div class="modal-content">
        <div class="modal-header"><h3>Create Streamer</h3><button onclick="closeModal('streamer-modal')" class="btn-close">×</button></div>
        <form id="streamer-form">
            <div class="form-group"><label>Username</label><input type="text" name="streamer_username" required></div>
            <div class="form-group"><label>Password</label><input type="text" name="streamer_password" required></div>
            <div class="form-group"><label>Display Name</label><input type="text" name="display_name"></div>
            <label class="checkbox"><input type="checkbox" name="is_active" checked> Active</label>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
</div>

<script>
const STATION = <?= $currentStation['id'] ?>;

async function api(action, method = 'GET', data = null) {
    const opts = { method };
    if (data) { opts.headers = {'Content-Type': 'application/json'}; opts.body = JSON.stringify(data); }
    return fetch(`admin.php?ajax=${action}&station=${STATION}`, opts).then(r => r.json());
}

async function loadAll() {
    // Now Playing
    const np = await api('ac-nowplaying');
    if (np.now_playing) {
        document.getElementById('np-display').innerHTML = `
            <strong>${np.now_playing.song?.title || 'Unknown'}</strong><br>
            <span>${np.now_playing.song?.artist || ''}</span>
        `;
        document.getElementById('listeners-count').textContent = np.listeners?.current || 0;
        document.getElementById('station-status').innerHTML = np.live?.is_live ? '<span class="badge live">🔴 LIVE</span>' : '<span class="badge">AutoDJ</span>';
    }
    
    // Playlists
    const playlists = await api('ac-playlists');
    if (Array.isArray(playlists)) {
        document.getElementById('playlists-list').innerHTML = playlists.map(p => `
            <div class="list-row">
                <span class="badge ${p.is_enabled ? 'success' : 'muted'}">${p.is_enabled ? 'ON' : 'OFF'}</span>
                <strong>${p.name}</strong> <small>(${p.num_songs || 0} songs)</small>
                <div class="row-actions">
                    <button onclick="togglePlaylist(${p.id})" class="btn btn-xs">${p.is_enabled ? 'Disable' : 'Enable'}</button>
                    <button onclick="deletePlaylist(${p.id})" class="btn btn-xs btn-danger">×</button>
                </div>
            </div>
        `).join('') || '<p class="text-muted">No playlists</p>';
    }
    
    // Streamers
    const streamers = await api('ac-streamers');
    if (Array.isArray(streamers)) {
        document.getElementById('streamers-list').innerHTML = streamers.map(s => `
            <div class="list-row">
                <span class="badge ${s.is_active ? 'success' : 'muted'}">${s.is_active ? 'Active' : 'Inactive'}</span>
                <strong>${s.display_name || s.streamer_username}</strong>
                <div class="row-actions">
                    <button onclick="deleteStreamer(${s.id})" class="btn btn-xs btn-danger">×</button>
                </div>
            </div>
        `).join('') || '<p class="text-muted">No streamers</p>';
    }
    
    // Queue
    const queue = await api('ac-queue');
    if (Array.isArray(queue)) {
        document.getElementById('queue-list').innerHTML = queue.map((q,i) => `
            <div class="list-row"><span>${i+1}.</span> ${q.song?.artist} - ${q.song?.title}</div>
        `).join('') || '<p class="text-muted">Queue empty</p>';
    }
    
    // Mounts
    const mounts = await api('ac-mounts');
    if (Array.isArray(mounts)) {
        document.getElementById('mounts-list').innerHTML = mounts.map(m => `
            <div class="list-row"><strong>${m.name}</strong> <code>${m.url}</code> <span class="badge">${m.listeners_current || 0} listeners</span></div>
        `).join('') || '<p class="text-muted">No mounts</p>';
    }
    
    // Media
    loadMedia('');
}

async function loadMedia(path) {
    document.getElementById('media-path').innerHTML = path ? `<a href="#" onclick="loadMedia('')">Root</a> / ${path}` : 'Root';
    const files = await api('ac-files&path=' + encodeURIComponent(path));
    if (Array.isArray(files)) {
        document.getElementById('media-list').innerHTML = files.slice(0, 30).map(f => 
            f.is_dir ? `<div class="list-row"><a href="#" onclick="loadMedia('${f.path}')">📁 ${f.path_short}</a></div>` :
            `<div class="list-row">🎵 ${f.path_short}</div>`
        ).join('') || '<p class="text-muted">Empty</p>';
    }
}

async function skipTrack() { if(confirm('Skip current track?')) { await api('ac-skip', 'POST'); setTimeout(loadAll, 1000); }}
async function disconnectStreamer() { if(confirm('Disconnect live DJ?')) { await api('ac-disconnect', 'POST'); setTimeout(loadAll, 1000); }}
async function restartStation() { if(confirm('Restart station?')) { await api('ac-restart', 'POST'); alert('Restart sent'); }}
async function clearQueue() { if(confirm('Clear queue?')) { await api('ac-clear-queue', 'POST'); loadAll(); }}
async function togglePlaylist(id) { await api('ac-toggle-playlist', 'POST', {playlist_id: id}); loadAll(); }
async function deletePlaylist(id) { if(confirm('Delete playlist?')) { await api('ac-delete-playlist', 'POST', {playlist_id: id}); loadAll(); }}
async function deleteStreamer(id) { if(confirm('Delete streamer?')) { await api('ac-delete-streamer', 'POST', {streamer_id: id}); loadAll(); }}

function showCreatePlaylist() { document.getElementById('playlist-modal').style.display = 'flex'; }
function showCreateStreamer() { document.getElementById('streamer-modal').style.display = 'flex'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }

document.getElementById('playlist-form').onsubmit = async (e) => {
    e.preventDefault();
    const fd = new FormData(e.target);
    await api('ac-create-playlist', 'POST', {name: fd.get('name'), type: fd.get('type'), weight: +fd.get('weight'), is_enabled: !!fd.get('is_enabled')});
    closeModal('playlist-modal'); e.target.reset(); loadAll();
};

document.getElementById('streamer-form').onsubmit = async (e) => {
    e.preventDefault();
    const fd = new FormData(e.target);
    await api('ac-create-streamer', 'POST', {streamer_username: fd.get('streamer_username'), streamer_password: fd.get('streamer_password'), display_name: fd.get('display_name'), is_active: !!fd.get('is_active')});
    closeModal('streamer-modal'); e.target.reset(); loadAll();
};

loadAll();
setInterval(loadAll, 10000);
</script>
<?php endif; ?>
