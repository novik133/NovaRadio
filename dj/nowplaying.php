<div class="card">
    <div class="card-header">
        <h3>🎵 Now Playing</h3>
        <?php if ($dj['can_stream']): ?>
        <button onclick="skipSong()" class="btn btn-sm btn-danger">Skip Track ⏭️</button>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <div class="np-full">
            <img id="np-artwork" src="assets/img/placeholder.png" class="np-artwork-lg">
            <div class="np-info">
                <h1 id="np-title">Loading...</h1>
                <h2 id="np-artist">-</h2>
                <p id="np-album" class="text-muted">-</p>
                <div class="np-meta">
                    <span id="np-listeners">👥 0 listeners</span>
                    <span id="np-bitrate">-</span>
                </div>
                <div class="np-progress-full">
                    <div class="progress-bar-lg"><div id="np-progress" class="progress-fill"></div></div>
                    <div class="progress-times">
                        <span id="np-elapsed">0:00</span>
                        <span id="np-duration">0:00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row-2">
    <div class="card">
        <div class="card-header"><h3>📜 Play History</h3></div>
        <div class="card-body" id="history-container" style="max-height:400px;overflow-y:auto"></div>
    </div>
    <div class="card">
        <div class="card-header"><h3>👥 Current Listeners</h3></div>
        <div class="card-body" id="listeners-container" style="max-height:400px;overflow-y:auto"></div>
    </div>
</div>

<script>
async function loadNowPlaying() {
    const np = await fetch('dj.php?ajax=nowplaying&station='+STATION_ID).then(r=>r.json());
    if (np.now_playing) {
        document.getElementById('np-title').textContent = np.now_playing.song?.title || 'Unknown';
        document.getElementById('np-artist').textContent = np.now_playing.song?.artist || 'Unknown';
        document.getElementById('np-album').textContent = np.now_playing.song?.album || '';
        document.getElementById('np-artwork').src = np.now_playing.song?.art || 'assets/img/placeholder.png';
        document.getElementById('np-listeners').textContent = '👥 ' + (np.listeners?.current || 0) + ' listeners';
        
        const elapsed = np.now_playing.elapsed || 0;
        const duration = np.now_playing.duration || 1;
        document.getElementById('np-progress').style.width = (elapsed/duration*100)+'%';
        document.getElementById('np-elapsed').textContent = formatTime(elapsed);
        document.getElementById('np-duration').textContent = formatTime(duration);
        
        document.getElementById('live-status').textContent = np.live?.is_live ? '🔴 LIVE' : '🟢 AutoDJ';
        document.getElementById('listener-count').textContent = (np.listeners?.current || 0) + ' listeners';
    }
    
    const history = await fetch('dj.php?ajax=history&station='+STATION_ID).then(r=>r.json());
    if (Array.isArray(history)) {
        document.getElementById('history-container').innerHTML = history.map(h => `
            <div class="history-row">
                <img src="${h.song?.art || 'assets/img/placeholder.png'}">
                <div class="history-info">
                    <strong>${h.song?.title || 'Unknown'}</strong>
                    <span>${h.song?.artist || ''}</span>
                </div>
                <span class="history-time">${new Date(h.played_at*1000).toLocaleTimeString()}</span>
            </div>
        `).join('');
    }
    
    const listeners = await fetch('dj.php?ajax=listeners&station='+STATION_ID).then(r=>r.json());
    if (Array.isArray(listeners)) {
        document.getElementById('listeners-container').innerHTML = listeners.length ? listeners.map(l => `
            <div class="listener-row">
                <span class="listener-ip">${l.ip}</span>
                <span class="listener-agent">${l.user_agent?.substring(0,30) || 'Unknown'}</span>
                <span class="listener-time">${Math.floor(l.connected_seconds/60)}m</span>
            </div>
        `).join('') : '<p class="text-muted">No listeners connected</p>';
    }
}

function formatTime(s) { return Math.floor(s/60)+':'+String(Math.floor(s%60)).padStart(2,'0'); }

async function skipSong() {
    if (!confirm('Skip current song?')) return;
    await fetch('dj.php?ajax=skip&station='+STATION_ID);
    setTimeout(loadNowPlaying, 1000);
}

loadNowPlaying();
setInterval(loadNowPlaying, 5000);
</script>
