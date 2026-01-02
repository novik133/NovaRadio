<div class="card">
    <div class="card-header">
        <h3>📜 Playback Queue</h3>
        <div class="header-actions">
            <button onclick="loadQueue()" class="btn btn-sm">🔄 Refresh</button>
            <?php if ($dj['can_playlist']): ?>
            <button onclick="clearQueue()" class="btn btn-sm btn-danger">🗑️ Clear Queue</button>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body">
        <div id="queue-list"></div>
    </div>
</div>

<script>
async function loadQueue() {
    const queue = await fetch('dj.php?ajax=queue&station='+STATION_ID).then(r=>r.json());
    if (Array.isArray(queue)) {
        document.getElementById('queue-list').innerHTML = queue.length ? queue.map((q, i) => `
            <div class="queue-row">
                <span class="queue-num">${i+1}</span>
                <img src="${q.song?.art || 'assets/img/placeholder.png'}" class="queue-art">
                <div class="queue-info">
                    <strong>${q.song?.title || 'Unknown'}</strong>
                    <span>${q.song?.artist || ''}</span>
                </div>
                <span class="queue-time">${q.cued_at ? new Date(q.cued_at*1000).toLocaleTimeString() : ''}</span>
                <span class="badge">${q.source || 'AutoDJ'}</span>
            </div>
        `).join('') : '<p class="text-muted">Queue is empty</p>';
    }
}

async function clearQueue() {
    if (!confirm('Clear entire queue?')) return;
    await fetch('dj.php?ajax=clear-queue&station='+STATION_ID);
    loadQueue();
}

loadQueue();
setInterval(loadQueue, 15000);
</script>
