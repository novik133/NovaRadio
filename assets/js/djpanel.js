// DJ Panel JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Update header status periodically
    updateHeaderStatus();
    setInterval(updateHeaderStatus, 15000);
});

async function updateHeaderStatus() {
    try {
        const np = await fetch('dj.php?ajax=nowplaying&station=' + STATION_ID).then(r => r.json());
        if (np) {
            const status = document.getElementById('live-status');
            const listeners = document.getElementById('listener-count');
            
            if (status) {
                if (np.live?.is_live) {
                    status.textContent = '🔴 LIVE';
                    status.className = 'status-badge live';
                } else {
                    status.textContent = '🟢 AutoDJ';
                    status.className = 'status-badge autodj';
                }
            }
            
            if (listeners) {
                listeners.textContent = (np.listeners?.current || 0) + ' listeners';
            }
        }
    } catch (e) {
        console.error('Failed to update status:', e);
    }
}

// Utility functions
function formatTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return mins + ':' + String(secs).padStart(2, '0');
}

function formatDate(timestamp) {
    return new Date(timestamp * 1000).toLocaleString();
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = 'notification ' + type;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => notification.classList.add('show'), 10);
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
