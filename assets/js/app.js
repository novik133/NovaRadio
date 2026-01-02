const player = document.getElementById('radio-player');
const playBtn = document.getElementById('play-btn');
const volumeSlider = document.getElementById('volume');
const playerBar = document.getElementById('player-bar');
let streamUrl = playerBar ? playerBar.getAttribute('data-stream') : '';
let currentStationId = playerBar ? playerBar.getAttribute('data-station') : '';
let isPlaying = false;
let audioContext, analyser, dataArray, animationId;

async function fetchNowPlaying(stationId) {
    try {
        const res = await fetch('api.php?action=nowplaying' + (stationId ? '&station=' + stationId : ''));
        return await res.json();
    } catch (e) { return null; }
}

function updateNowPlaying(data) {
    const artistEl = document.getElementById('np-artist');
    const titleEl = document.getElementById('np-title');
    const listenersEl = document.getElementById('listener-count');
    const artworkEl = document.getElementById('np-artwork');
    const liveIndicator = document.querySelector('.live-badge');
    
    if (data?.nowplaying?.now_playing) {
        const np = data.nowplaying.now_playing;
        if (artistEl) artistEl.textContent = np.song?.artist || 'Unknown Artist';
        if (titleEl) titleEl.textContent = np.song?.title || '';
        if (listenersEl) listenersEl.textContent = data.nowplaying.listeners?.current || 0;
        if (artworkEl && np.song?.art) artworkEl.src = np.song.art;
        document.title = `${np.song?.artist} - ${np.song?.title} | ${document.title.split('|').pop()}`;
    } else if (data?.now_playing) {
        if (artistEl) artistEl.textContent = data.now_playing.song?.artist || 'Unknown Artist';
        if (titleEl) titleEl.textContent = data.now_playing.song?.title || '';
        if (listenersEl) listenersEl.textContent = data.listeners?.current || 0;
    }
    
    if (data?.live?.is_live && liveIndicator) {
        liveIndicator.classList.add('pulse');
        liveIndicator.textContent = 'LIVE';
    }
}

function togglePlay() {
    if (!streamUrl) { console.error('No stream URL'); return; }
    if (isPlaying) {
        player.pause();
        player.currentTime = 0;
        if (playBtn) playBtn.innerHTML = '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>';
        isPlaying = false;
        stopVisualizer();
    } else {
        if (!player.src || player.src !== streamUrl) player.src = streamUrl;
        player.play().then(() => {
            if (playBtn) playBtn.innerHTML = '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>';
            isPlaying = true;
            initVisualizer();
        }).catch(e => console.error('Play failed:', e));
    }
}
window.togglePlay = togglePlay;

function initVisualizer() {
    const canvas = document.getElementById('visualizer');
    if (!canvas || !getSetting('visualizer_enabled')) return;
    
    try {
        audioContext = audioContext || new (window.AudioContext || window.webkitAudioContext)();
        if (!analyser) {
            analyser = audioContext.createAnalyser();
            const source = audioContext.createMediaElementSource(player);
            source.connect(analyser);
            analyser.connect(audioContext.destination);
            analyser.fftSize = 64;
            dataArray = new Uint8Array(analyser.frequencyBinCount);
        }
        drawVisualizer(canvas);
    } catch (e) { console.log('Visualizer not supported'); }
}

function drawVisualizer(canvas) {
    const ctx = canvas.getContext('2d');
    const width = canvas.width = canvas.offsetWidth;
    const height = canvas.height = canvas.offsetHeight;
    
    function draw() {
        animationId = requestAnimationFrame(draw);
        analyser.getByteFrequencyData(dataArray);
        ctx.clearRect(0, 0, width, height);
        const barWidth = width / dataArray.length;
        dataArray.forEach((value, i) => {
            const barHeight = (value / 255) * height;
            const hue = (i / dataArray.length) * 60 + 220;
            ctx.fillStyle = `hsla(${hue}, 80%, 60%, 0.8)`;
            ctx.fillRect(i * barWidth, height - barHeight, barWidth - 1, barHeight);
        });
    }
    draw();
}

function stopVisualizer() {
    if (animationId) cancelAnimationFrame(animationId);
}

function getSetting(key) {
    return document.body.dataset[key] !== '0';
}

// Track Reactions
async function reactToTrack(reaction) {
    const artist = document.getElementById('np-artist')?.textContent;
    const title = document.getElementById('np-title')?.textContent;
    if (!artist || !title) return;
    
    const res = await fetch('ajax.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=track_reaction&reaction=${reaction}&artist=${encodeURIComponent(artist)}&title=${encodeURIComponent(title)}&station_id=${currentStationId}`
    });
    const data = await res.json();
    if (data.success) {
        document.querySelector(`.reaction-btn.${reaction}`)?.classList.add('active');
        showToast(reaction === 'like' ? '❤️ Liked!' : '👎 Noted');
    }
}
window.reactToTrack = reactToTrack;

function showToast(message, duration = 2000) {
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 10);
    setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.remove(), 300); }, duration);
}
window.showToast = showToast;

function switchStation(stationId, newStreamUrl, stationName) {
    document.querySelectorAll('.station-tab').forEach(t => t.classList.remove('active'));
    document.querySelector(`.station-tab[data-station="${stationId}"]`)?.classList.add('active');
    streamUrl = newStreamUrl;
    currentStationId = stationId;
    playerBar?.setAttribute('data-stream', newStreamUrl);
    playerBar?.setAttribute('data-station', stationId);
    if (isPlaying) { player.src = streamUrl; player.play(); }
    fetchNowPlaying(stationId).then(updateNowPlaying);
}

document.querySelectorAll('.station-tab').forEach(tab => {
    tab.addEventListener('click', () => switchStation(tab.dataset.station, tab.dataset.stream, tab.dataset.name));
});

if (volumeSlider) {
    volumeSlider.addEventListener('input', (e) => player.volume = e.target.value / 100);
    player.volume = 0.8;
}

if (playBtn) playBtn.addEventListener('click', togglePlay);

// Keyboard shortcuts
document.addEventListener('keydown', (e) => {
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
    if (e.code === 'Space') { e.preventDefault(); togglePlay(); }
    if (e.code === 'ArrowUp') { e.preventDefault(); volumeSlider.value = Math.min(100, +volumeSlider.value + 5); player.volume = volumeSlider.value / 100; }
    if (e.code === 'ArrowDown') { e.preventDefault(); volumeSlider.value = Math.max(0, +volumeSlider.value - 5); player.volume = volumeSlider.value / 100; }
    if (e.code === 'KeyM') { player.muted = !player.muted; showToast(player.muted ? '🔇 Muted' : '🔊 Unmuted'); }
});

async function loadHistory() {
    const container = document.getElementById('history-list');
    if (!container) return;
    try {
        const res = await fetch('api.php?action=history' + (currentStationId ? '&station=' + currentStationId : ''));
        const history = await res.json();
        if (!history.length) { container.innerHTML = '<p class="text-muted">No history available</p>'; return; }
        container.innerHTML = history.slice(0, 10).map(item => `
            <div class="history-item">
                <img src="${item.song?.art || item.artwork || 'assets/img/placeholder.png'}" alt="" onerror="this.src='assets/img/placeholder.png'">
                <div class="history-info">
                    <strong>${item.song?.artist || item.artist || 'Unknown'}</strong>
                    <span>${item.song?.title || item.title || 'Unknown'}</span>
                </div>
                <span class="history-time">${item.played_at ? new Date(item.played_at).toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'}) : ''}</span>
            </div>
        `).join('');
    } catch (e) { container.innerHTML = '<p class="text-muted">Unable to load history</p>'; }
}

document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.schedule-day').forEach(d => d.classList.remove('active'));
        btn.classList.add('active');
        document.querySelector(`.schedule-day[data-day="${btn.dataset.day}"]`)?.classList.add('active');
    });
});

// Theme toggle with system preference
const themeToggle = document.getElementById('theme-toggle');
const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');
const savedTheme = localStorage.getItem('theme') || (prefersDark.matches ? 'dark' : 'light');
document.documentElement.setAttribute('data-theme', savedTheme);
if (themeToggle) {
    themeToggle.innerHTML = savedTheme === 'light' ? '🌙' : '☀️';
    themeToggle.addEventListener('click', () => {
        const next = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', next);
        localStorage.setItem('theme', next);
        themeToggle.innerHTML = next === 'light' ? '🌙' : '☀️';
    });
}

// Mobile nav
document.querySelector('.nav-toggle')?.addEventListener('click', () => {
    document.querySelector('.nav-links')?.classList.toggle('show');
});

// Share functionality
window.shareTrack = async function() {
    const artist = document.getElementById('np-artist')?.textContent;
    const title = document.getElementById('np-title')?.textContent;
    const text = `🎵 Listening to ${artist} - ${title} on ${document.title.split('|').pop()?.trim()}`;
    if (navigator.share) {
        await navigator.share({ title: 'Now Playing', text, url: location.href });
    } else {
        navigator.clipboard.writeText(text + ' ' + location.href);
        showToast('📋 Copied to clipboard!');
    }
};

// Sleep timer
let sleepTimer;
window.setSleepTimer = function(minutes) {
    clearTimeout(sleepTimer);
    if (minutes > 0) {
        sleepTimer = setTimeout(() => { player.pause(); isPlaying = false; showToast('💤 Sleep timer - Radio stopped'); }, minutes * 60000);
        showToast(`⏰ Sleep timer set for ${minutes} minutes`);
    }
};

document.addEventListener('DOMContentLoaded', async () => {
    updateNowPlaying(await fetchNowPlaying(currentStationId));
    loadHistory();
    setInterval(async () => updateNowPlaying(await fetchNowPlaying(currentStationId)), 15000);
    
    // Slider
    const slides = document.querySelectorAll('.slide');
    if (slides.length > 1) {
        let current = 0;
        const showSlide = (n) => {
            slides[current].classList.remove('active');
            current = (n + slides.length) % slides.length;
            slides[current].classList.add('active');
        };
        document.querySelector('.slider-prev')?.addEventListener('click', () => showSlide(current - 1));
        document.querySelector('.slider-next')?.addEventListener('click', () => showSlide(current + 1));
        setInterval(() => showSlide(current + 1), 5000);
    }
    
    // Register service worker for PWA
    if ('serviceWorker' in navigator && document.body.dataset.pwa !== '0') {
        navigator.serviceWorker.register('sw.js').catch(() => {});
    }
});
