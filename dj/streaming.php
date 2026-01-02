<?php
$streamerInfo = null;
if ($dj['azuracast_dj_id']) {
    $streamerInfo = ac_getStreamer($currentStation, $dj['azuracast_dj_id']);
}
?>

<div class="card">
    <div class="card-header">
        <h3>🎙️ Live Streaming</h3>
        <span id="stream-status" class="status-badge">Checking...</span>
    </div>
    <div class="card-body">
        <?php if ($streamerInfo && !isset($streamerInfo['error'])): ?>
        <div class="stream-info">
            <div class="stream-credentials">
                <h4>Your Streaming Credentials</h4>
                <table class="credentials-table">
                    <tr><td>Server:</td><td><code><?= e(rtrim($currentStation['azuracast_url'], '/')) ?></code></td></tr>
                    <tr><td>Port:</td><td><code>8000</code> (or 8443 for SSL)</td></tr>
                    <tr><td>Mount:</td><td><code>/live</code></td></tr>
                    <tr><td>Username:</td><td><code><?= e($dj['azuracast_username'] ?: $streamerInfo['streamer_username'] ?? 'N/A') ?></code></td></tr>
                    <tr><td>Password:</td><td><code id="stream-pass">••••••••</code> <button onclick="togglePassword()" class="btn btn-sm">Show</button></td></tr>
                </table>
            </div>
            
            <div class="stream-software">
                <h4>Recommended Software</h4>
                <div class="software-grid">
                    <a href="https://www.butt.sourceforge.net/" target="_blank" class="software-card">
                        <strong>BUTT</strong>
                        <span>Simple, lightweight</span>
                    </a>
                    <a href="https://obsproject.com/" target="_blank" class="software-card">
                        <strong>OBS Studio</strong>
                        <span>Video + Audio</span>
                    </a>
                    <a href="https://mixxx.org/" target="_blank" class="software-card">
                        <strong>Mixxx</strong>
                        <span>DJ Software</span>
                    </a>
                    <a href="https://www.virtualdj.com/" target="_blank" class="software-card">
                        <strong>Virtual DJ</strong>
                        <span>Professional DJ</span>
                    </a>
                </div>
            </div>
            
            <div class="stream-actions">
                <button onclick="disconnectStreamer()" class="btn btn-danger">Disconnect Current Streamer</button>
                <button onclick="restartStation()" class="btn btn-warning">🔄 Restart Station</button>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-warning">
            <p>Your AzuraCast streamer account is not linked. Please contact an administrator to set up your streaming credentials.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3>📡 Stream Mounts</h3></div>
    <div class="card-body">
        <div id="mounts-list"></div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3>📋 Quick Setup Guide</h3></div>
    <div class="card-body">
        <ol class="setup-guide">
            <li>Download and install one of the recommended streaming software</li>
            <li>Open the software and go to Settings/Preferences</li>
            <li>Enter the server details shown above</li>
            <li>Set your audio input (microphone, mixer, etc.)</li>
            <li>Choose your bitrate (128kbps recommended)</li>
            <li>Click Connect/Start Streaming</li>
            <li>Your stream will automatically take over from AutoDJ</li>
        </ol>
    </div>
</div>

<script>
const STREAM_PASS = '<?= e($dj['azuracast_password'] ?? '') ?>';

function togglePassword() {
    const el = document.getElementById('stream-pass');
    el.textContent = el.textContent === '••••••••' ? STREAM_PASS : '••••••••';
}

async function loadStreamInfo() {
    const np = await fetch('dj.php?ajax=nowplaying&station='+STATION_ID).then(r=>r.json());
    const status = document.getElementById('stream-status');
    if (np.live?.is_live) {
        status.textContent = '🔴 LIVE - ' + (np.live.streamer_name || 'Unknown');
        status.className = 'status-badge live';
    } else {
        status.textContent = '🟢 AutoDJ Active';
        status.className = 'status-badge autodj';
    }
    
    const mounts = await fetch('dj.php?ajax=mounts&station='+STATION_ID).then(r=>r.json());
    if (Array.isArray(mounts)) {
        document.getElementById('mounts-list').innerHTML = mounts.map(m => `
            <div class="mount-item">
                <strong>${m.name}</strong>
                <span>${m.url}</span>
                <span class="badge">${m.listeners_current || 0} listeners</span>
            </div>
        `).join('') || '<p class="text-muted">No mounts available</p>';
    }
}

async function disconnectStreamer() {
    if (!confirm('Disconnect current live streamer?')) return;
    await fetch('dj.php?ajax=disconnect&station='+STATION_ID);
    alert('Disconnect command sent');
    loadStreamInfo();
}

async function restartStation() {
    if (!confirm('Restart the station backend? This will briefly interrupt playback.')) return;
    await fetch('dj.php?ajax=restart&station='+STATION_ID);
    alert('Restart command sent');
    loadStreamInfo();
}

loadStreamInfo();
setInterval(loadStreamInfo, 10000);
</script>
