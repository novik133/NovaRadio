document.addEventListener('DOMContentLoaded', function() {
    // Header scroll effect
    const header = document.querySelector('.site-header');
    
    function updateHeader() {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }
    
    window.addEventListener('scroll', updateHeader);
    updateHeader();

    // Mobile menu toggle
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const navDesktop = document.querySelector('.nav-desktop');

    if (menuToggle && navDesktop) {
        menuToggle.addEventListener('click', function() {
            navDesktop.classList.toggle('show');
        });
    }

    // Audio player
    const audioPlayer = document.getElementById('audio-player');
    const playButton = document.getElementById('play-button');
    const volumeSlider = document.getElementById('volume-slider');

    if (audioPlayer && playButton) {
        let isPlaying = false;

        playButton.addEventListener('click', function() {
            if (isPlaying) {
                audioPlayer.pause();
                playButton.innerHTML = '<i class="fas fa-play"></i>';
            } else {
                audioPlayer.load();
                audioPlayer.play().then(() => {
                    playButton.innerHTML = '<i class="fas fa-pause"></i>';
                }).catch(() => {
                    alert('Click play to start audio');
                });
            }
            isPlaying = !isPlaying;
        });

        if (volumeSlider) {
            volumeSlider.addEventListener('input', function() {
                audioPlayer.volume = this.value / 100;
            });
        }
    }

    // Modern Cookie Banner with Granular Consent
    const cookieBanner = document.getElementById('cookie-banner');
    const acceptAllBtn = document.getElementById('accept-all');
    const essentialOnlyBtn = document.getElementById('essential-only');
    const customizeBtn = document.getElementById('customize-cookies');
    const analyticsCheckbox = document.getElementById('analytics-cookies');
    const functionalCheckbox = document.getElementById('functional-cookies');

    // Check if user has already made a choice
    const cookieConsent = localStorage.getItem('cookie_consent');

    if (cookieBanner && !cookieConsent) {
        // Show banner in compact mode initially
        cookieBanner.classList.add('show', 'compact');
    }

    // Expand banner when clicking customize
    if (customizeBtn && cookieBanner) {
        customizeBtn.addEventListener('click', function() {
            cookieBanner.classList.remove('compact');
        });
    }

    // Accept All Cookies
    if (acceptAllBtn) {
        acceptAllBtn.addEventListener('click', function() {
            const consent = {
                essential: true,
                analytics: true,
                functional: true,
                timestamp: new Date().toISOString(),
                version: '1.0'
            };
            localStorage.setItem('cookie_consent', JSON.stringify(consent));
            cookieBanner.classList.remove('show');

            // Check the checkboxes visually
            if (analyticsCheckbox) analyticsCheckbox.checked = true;
            if (functionalCheckbox) functionalCheckbox.checked = true;
        });
    }

    // Essential Only
    if (essentialOnlyBtn) {
        essentialOnlyBtn.addEventListener('click', function() {
            const consent = {
                essential: true,
                analytics: false,
                functional: false,
                timestamp: new Date().toISOString(),
                version: '1.0'
            };
            localStorage.setItem('cookie_consent', JSON.stringify(consent));
            cookieBanner.classList.remove('show');

            // Uncheck optional checkboxes
            if (analyticsCheckbox) analyticsCheckbox.checked = false;
            if (functionalCheckbox) functionalCheckbox.checked = false;
        });
    }

    // Save custom preferences when toggling checkboxes (if in customize mode)
    function saveCustomConsent() {
        const consent = {
            essential: true,
            analytics: analyticsCheckbox ? analyticsCheckbox.checked : false,
            functional: functionalCheckbox ? functionalCheckbox.checked : false,
            timestamp: new Date().toISOString(),
            version: '1.0'
        };
        localStorage.setItem('cookie_consent', JSON.stringify(consent));
        cookieBanner.classList.remove('show');
    }

    if (analyticsCheckbox) {
        analyticsCheckbox.addEventListener('change', function() {
            if (!cookieBanner.classList.contains('compact')) {
                saveCustomConsent();
            }
        });
    }

    if (functionalCheckbox) {
        functionalCheckbox.addEventListener('change', function() {
            if (!cookieBanner.classList.contains('compact')) {
                saveCustomConsent();
            }
        });
    }

    // Restore checkbox states from saved consent
    if (cookieConsent) {
        try {
            const consent = JSON.parse(cookieConsent);
            if (analyticsCheckbox) analyticsCheckbox.checked = consent.analytics || false;
            if (functionalCheckbox) functionalCheckbox.checked = consent.functional || false;
        } catch (e) {
            console.log('Error parsing cookie consent');
        }
    }

    // Now playing update
    function updateNowPlaying() {
        fetch('/api/nowplaying')
            .then(r => r.json())
            .then(data => {
                if (data.now_playing) {
                    const title = document.querySelector('.track-title');
                    const artist = document.querySelector('.track-artist');
                    const listeners = document.querySelector('.listeners-count');

                    if (title) title.textContent = data.now_playing.song.title || 'Unknown';
                    if (artist) artist.textContent = data.now_playing.song.artist || 'Unknown Artist';
                    if (listeners) listeners.textContent = data.listeners?.total || 0;

                    // Update live status
                    const liveDot = document.querySelector('.live-dot');
                    const heroBadge = document.querySelector('.hero-badge');
                    const isLive = data.now_playing && data.now_playing.song;
                    
                    if (liveDot) {
                        if (isLive) {
                            liveDot.classList.remove('offline');
                        } else {
                            liveDot.classList.add('offline');
                        }
                    }
                    
                    if (heroBadge) {
                        const badgeText = heroBadge.childNodes[heroBadge.childNodes.length - 1];
                        if (badgeText && badgeText.nodeType === 3) {
                            badgeText.textContent = isLive ? 'Live Broadcasting' : 'Radio Offline';
                        }
                    }

                    // Update hero background with album art
                    const heroSection = document.querySelector('.hero');
                    const heroBackground = heroSection?.querySelector('[style*="background-image"]');
                    const nowPlayingCard = document.querySelector('.hero [style*="backdrop-filter"]');
                    
                    if (data.now_playing.song.art && heroBackground) {
                        const currentBg = heroBackground.style.backgroundImage;
                        const newBg = `url('${data.now_playing.song.art}')`;
                        
                        // Only update if different
                        if (currentBg !== newBg) {
                            heroBackground.style.transition = 'opacity 1s ease-in-out';
                            heroBackground.style.opacity = '0';
                            
                            setTimeout(() => {
                                heroBackground.style.backgroundImage = newBg;
                                heroBackground.style.opacity = '1';
                            }, 1000);
                        }
                    }

                    // Update now playing card in hero
                    if (nowPlayingCard) {
                        const cardImg = nowPlayingCard.querySelector('img');
                        const cardTitle = nowPlayingCard.querySelector('[style*="font-size: 16px"]');
                        const cardArtist = nowPlayingCard.querySelector('[style*="font-size: 14px"]');

                        if (cardImg && data.now_playing.song.art) {
                            cardImg.src = data.now_playing.song.art;
                        }
                        if (cardTitle) cardTitle.textContent = data.now_playing.song.title || 'Unknown Track';
                        if (cardArtist) cardArtist.textContent = data.now_playing.song.artist || 'Unknown Artist';
                    }
                }
            })
            .catch(() => {});
    }

    if (document.querySelector('.radio-player')) {
        updateNowPlaying();
        setInterval(updateNowPlaying, 30000);
    }

    // PWA Service Worker
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js')
            .then(() => console.log('SW registered'))
            .catch(() => console.log('SW registration failed'));
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
});
