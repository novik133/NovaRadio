<div class="card">
    <div class="card-header">
        <h3>📋 Playlists</h3>
        <button onclick="showCreatePlaylist()" class="btn btn-primary">+ New Playlist</button>
    </div>
    <div class="card-body">
        <div id="playlists-list"></div>
    </div>
</div>

<div id="create-modal" class="modal" style="display:none">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Create Playlist</h3>
            <button onclick="closeModal()" class="btn-close">×</button>
        </div>
        <form id="playlist-form">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Type</label>
                <select name="type">
                    <option value="default">General Rotation</option>
                    <option value="once_per_x_songs">Once per X Songs</option>
                    <option value="once_per_x_minutes">Once per X Minutes</option>
                    <option value="once_per_hour">Once per Hour</option>
                    <option value="advanced">Advanced</option>
                </select>
            </div>
            <div class="form-group">
                <label>Weight (1-25)</label>
                <input type="number" name="weight" value="3" min="1" max="25">
            </div>
            <div class="form-row">
                <label class="checkbox"><input type="checkbox" name="is_enabled" checked> Enabled</label>
                <label class="checkbox"><input type="checkbox" name="include_in_requests"> Allow Requests</label>
            </div>
            <button type="submit" class="btn btn-primary">Create Playlist</button>
        </form>
    </div>
</div>

<script>
async function loadPlaylists() {
    const playlists = await fetch('dj.php?ajax=playlists&station='+STATION_ID).then(r=>r.json());
    if (Array.isArray(playlists)) {
        document.getElementById('playlists-list').innerHTML = playlists.map(p => `
            <div class="playlist-card ${p.is_enabled ? '' : 'disabled'}">
                <div class="playlist-info">
                    <h4>${p.name}</h4>
                    <span class="badge">${p.type}</span>
                    <span class="text-muted">${p.num_songs || 0} songs</span>
                </div>
                <div class="playlist-actions">
                    <button onclick="togglePlaylist(${p.id})" class="btn btn-sm ${p.is_enabled ? 'btn-warning' : 'btn-success'}">
                        ${p.is_enabled ? 'Disable' : 'Enable'}
                    </button>
                </div>
            </div>
        `).join('') || '<p class="text-muted">No playlists found</p>';
    } else if (playlists.error) {
        document.getElementById('playlists-list').innerHTML = '<p class="text-muted">'+playlists.error+'</p>';
    }
}

function showCreatePlaylist() {
    document.getElementById('create-modal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('create-modal').style.display = 'none';
}

document.getElementById('playlist-form').onsubmit = async (e) => {
    e.preventDefault();
    const fd = new FormData(e.target);
    const data = {
        name: fd.get('name'),
        type: fd.get('type'),
        weight: parseInt(fd.get('weight')),
        is_enabled: fd.get('is_enabled') === 'on',
        include_in_requests: fd.get('include_in_requests') === 'on'
    };
    const res = await fetch('dj.php?ajax=create-playlist&station='+STATION_ID, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
    });
    const result = await res.json();
    if (result.id) {
        closeModal();
        loadPlaylists();
    } else {
        alert(result.error || 'Error creating playlist');
    }
};

async function togglePlaylist(id) {
    await fetch('dj.php?ajax=toggle-playlist&station='+STATION_ID, {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'playlist_id='+id
    });
    loadPlaylists();
}

loadPlaylists();
</script>
