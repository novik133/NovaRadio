<div class="dashboard-grid">
    <div class="card stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-info">
            <span class="stat-value" id="dash-listeners">-</span>
            <span class="stat-label">Current Listeners</span>
        </div>
    </div>
    <div class="card stat-card">
        <div class="stat-icon">🎵</div>
        <div class="stat-info">
            <span class="stat-value" id="dash-track">-</span>
            <span class="stat-label">Now Playing</span>
        </div>
    </div>
    <div class="card stat-card">
        <div class="stat-icon">📋</div>
        <div class="stat-info">
            <span class="stat-value" id="dash-queue">-</span>
            <span class="stat-label">Queue Length</span>
        </div>
    </div>
    <div class="card stat-card">
        <div class="stat-icon">🎶</div>
        <div class="stat-info">
            <span class="stat-value" id="dash-requests">-</span>
            <span class="stat-label">Pending Requests</span>
        </div>
    </div>
</div>

<div class="dashboard-row">
    <div class="card">
        <div class="card-header">
            <h3>Now Playing</h3>
            <?php if ($dj['can_stream']): ?>
            <button onclick="skipSong()" class="btn btn-sm">Skip ⏭️</button>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div id="now-playing-info" class="now-playing-large">
                <img id="np-art" src="assets/img/placeholder.png" class="np-artwork">
                <div class="np-details">
                    <h2 id="np-title">Loading...</h2>
                    <p id="np-artist">-</p>
                    <div class="np-progress">
                        <div class="progress-bar"><div id="np-progress" class="progress-fill"></div></div>
                        <span id="np-time">0:00 / 0:00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header"><h3>Recent History</h3></div>
        <div class="card-body">
            <div id="history-list" class="history-compact"></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3>Upcoming Queue</h3></div>
    <div class="card-body">
        <div id="queue-preview" class="queue-list"></div>
    </div>
</div>

<script>
async function loadDashboard() {
    const np = await fetch('dj.php?ajax=nowplaying&station='+STATION_ID).then(r=>r.json());
    if (np.now_playing) {
        document.getElementById('np-title').textContent = np.now_playing.song?.title || 'Unknown';
        document.getElementById('np-artist').textContent = np.now_playing.song?.artist || 'Unknown';
        document.getElementById('np-art').src = np.now_playing.song?.art || 'assets/img/placeholder.png';
        document.getElementById('dash-track').textContent = np.now_playing.song?.title?.substring(0,20) || '-';
        document.getElementById('dash-listeners').textContent = np.listeners?.current || 0;
        document.getElementById('listener-count').textContent = (np.listeners?.current || 0) + ' listeners';
        
        const elapsed = np.now_playing.elapsed || 0;
        const duration = np.now_playing.duration || 1;
        document.getElementById('np-progress').style.width = (elapsed/duration*100)+'%';
        document.getElementById('np-time').textContent = formatTime(elapsed) + ' / ' + formatTime(duration);
        
        document.getElementById('live-status').textContent = np.live?.is_live ? '🔴 LIVE' : '🟢 AutoDJ';
        document.getElementById('live-status').className = 'status-badge ' + (np.live?.is_live ? 'live' : 'autodj');
    }
    
    const history = await fetch('dj.php?ajax=history&station='+STATION_ID).then(r=>r.json());
    if (Array.isArray(history)) {
        document.getElementById('history-list').innerHTML = history.slice(0,5).map(h => `
            <div class="history-item-sm">
                <img src="${h.song?.art || 'assets/img/placeholder.png'}">
                <div><strong>${h.song?.title || 'Unknown'}</strong><br><small>${h.song?.artist || ''}</small></div>
            </div>
        `).join('');
    }
    
    const queue = await fetch('dj.php?ajax=queue&station='+STATION_ID).then(r=>r.json());
    if (Array.isArray(queue)) {
        document.getElementById('dash-queue').textContent = queue.length;
        document.getElementById('queue-preview').innerHTML = queue.slice(0,5).map((q,i) => `
            <div class="queue-item-sm"><span>#${i+1}</span> ${q.song?.title || 'Unknown'} - ${q.song?.artist || ''}</div>
        `).join('') || '<p class="text-muted">Queue empty</p>';
    }
    
    const requests = await fetch('dj.php?ajax=requests&station='+STATION_ID).then(r=>r.json());
    document.getElementById('dash-requests').textContent = Array.isArray(requests) ? requests.length : 0;
}

function formatTime(s) { return Math.floor(s/60)+':'+String(Math.floor(s%60)).padStart(2,'0'); }

async function skipSong() {
    if (!confirm('Skip current song?')) return;
    await fetch('dj.php?ajax=skip&station='+STATION_ID);
    setTimeout(loadDashboard, 1000);
}

loadDashboard();
setInterval(loadDashboard, 10000);
</script>
