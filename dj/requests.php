<div class="card">
    <div class="card-header">
        <h3>🎶 Song Requests</h3>
        <button onclick="loadRequests()" class="btn btn-sm">🔄 Refresh</button>
    </div>
    <div class="card-body">
        <div class="tabs">
            <button class="tab active" onclick="showTab('local')">Local Requests</button>
            <button class="tab" onclick="showTab('azuracast')">AzuraCast Queue</button>
        </div>
        <div id="tab-local" class="tab-content">
            <div id="local-requests"></div>
        </div>
        <div id="tab-azuracast" class="tab-content" style="display:none">
            <div id="azuracast-requests"></div>
        </div>
    </div>
</div>

<script>
function showTab(tab) {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
    event.target.classList.add('active');
    document.getElementById('tab-' + tab).style.display = 'block';
}

async function loadRequests() {
    // Local requests from database
    const local = await fetch('dj.php?ajax=local-requests&station=' + STATION_ID).then(r => r.json());
    document.getElementById('local-requests').innerHTML = local.length ? local.map(r => `
        <div class="request-row" data-id="${r.id}">
            <div class="request-info">
                <strong>${r.artist} - ${r.title}</strong>
                <span>From: ${r.name || 'Anonymous'}${r.message ? ' - "' + r.message + '"' : ''}</span>
                <small>${new Date(r.created_at).toLocaleString()}</small>
            </div>
            <div class="request-status status-${r.status}">${r.status}</div>
            <div class="request-actions">
                ${r.status === 'pending' ? `
                    <button onclick="updateRequest(${r.id}, 'approved')" class="btn btn-sm btn-success">✓</button>
                    <button onclick="updateRequest(${r.id}, 'played')" class="btn btn-sm btn-primary">▶</button>
                    <button onclick="updateRequest(${r.id}, 'rejected')" class="btn btn-sm btn-danger">✕</button>
                ` : r.status === 'approved' ? `
                    <button onclick="updateRequest(${r.id}, 'played')" class="btn btn-sm btn-primary">▶ Played</button>
                ` : ''}
            </div>
        </div>
    `).join('') : '<p class="text-muted">No pending requests</p>';

    // AzuraCast requests
    const ac = await fetch('dj.php?ajax=requests&station=' + STATION_ID).then(r => r.json());
    document.getElementById('azuracast-requests').innerHTML = Array.isArray(ac) && ac.length ? ac.map(r => `
        <div class="request-row">
            <img src="${r.track?.song?.art || 'assets/img/placeholder.png'}" class="request-art">
            <div class="request-info">
                <strong>${r.track?.song?.title || 'Unknown'}</strong>
                <span>${r.track?.song?.artist || ''}</span>
            </div>
            <span class="request-time">${new Date(r.timestamp * 1000).toLocaleTimeString()}</span>
        </div>
    `).join('') : '<p class="text-muted">No AzuraCast requests</p>';
}

async function updateRequest(id, status) {
    await fetch('dj.php?ajax=update-request', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `id=${id}&status=${status}`
    });
    loadRequests();
}

loadRequests();
setInterval(loadRequests, 30000);
</script>
