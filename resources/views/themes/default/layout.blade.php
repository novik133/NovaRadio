<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $seo_title ?? ($siteName ?? 'NovaRadio') }}</title>
    <meta name="description" content="{{ $seo_description ?? '' }}">
    <meta name="keywords" content="{{ $seo_keywords ?? '' }}">
    <meta name="author" content="NovaRadio">
    <meta name="theme-color" content="#6366f1">

    <!-- Open Graph -->
    <meta property="og:title" content="{{ $seo_title ?? ($siteName ?? 'NovaRadio') }}">
    <meta property="og:description" content="{{ $seo_description ?? '' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $seo_og_image ?? asset('pwa/icon-512x512.png') }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo_title ?? ($siteName ?? 'NovaRadio') }}">
    <meta name="twitter:description" content="{{ $seo_description ?? '' }}">
    <meta name="twitter:image" content="{{ $seo_og_image ?? asset('pwa/icon-512x512.png') }}">

    <!-- PWA -->
    <link rel="manifest" href="{{ route('pwa.manifest') }}">
    <link rel="apple-touch-icon" href="{{ asset('pwa/icon-192x192.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('themes/default/css/app.css') }}">

    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="site-header">
        <div class="container header-content">
            <a href="{{ route('home') }}" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-broadcast-tower"></i>
                </div>
                <span>{{ $siteName ?? 'NovaRadio' }}</span>
            </a>

            <nav class="nav-desktop">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                <a href="{{ route('page.show', 'about') }}">About</a>
                <a href="{{ route('schedule') }}">Schedule</a>
                <a href="{{ route('team') }}">Team</a>
                <a href="{{ route('page.show', 'contact') }}">Contact</a>
            </nav>

            <button class="mobile-menu-toggle" aria-label="Menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="footer-brand">
                        <i class="fas fa-broadcast-tower"></i>
                        <span>{{ $siteName ?? 'NovaRadio' }}</span>
                    </div>
                    <p class="footer-description">{{ $siteTagline ?? 'Your Soundtrack to Life' }}. Listen to the best music curated just for you.</p>
                    <div class="footer-social">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <div>
                    <h4 class="footer-title">Navigation</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('page.show', 'about') }}">About</a></li>
                        <li><a href="{{ route('schedule') }}">Schedule</a></li>
                        <li><a href="{{ route('team') }}">Team</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="footer-title">Legal</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('page.show', 'privacy-policy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('page.show', 'terms-of-service') }}">Terms of Service</a></li>
                        <li><a href="{{ route('page.show', 'cookie-policy') }}">Cookie Policy</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="footer-title">Contact</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('page.show', 'contact') }}">Contact Us</a></li>
                        <li><a href="mailto:contact@novikradio.com">contact@novikradio.com</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} {{ $siteName ?? 'NovaRadio' }}. All rights reserved.</p>
                <p>Made with <i class="fas fa-heart" style="color: var(--color-secondary);"></i> for music lovers</p>
            </div>
        </div>
    </footer>

    <!-- Modern Cookie Banner -->
    <div id="cookie-banner" class="cookie-banner">
        <div class="cookie-backdrop"></div>
        <div class="cookie-modal">
            <div class="cookie-header">
                <div class="cookie-icon">
                    <i class="fas fa-cookie-bite"></i>
                </div>
                <h3>Cookie Preferences</h3>
                <p>We use cookies to enhance your browsing experience, serve personalized content, and analyze our traffic. Please select your preferences below.</p>
            </div>

            <div class="cookie-options">
                <div class="cookie-option cookie-option-disabled">
                    <div class="cookie-option-info">
                        <strong>Essential Cookies</strong>
                        <span>Required for the website to function properly. Cannot be disabled.</span>
                    </div>
                    <div class="cookie-toggle">
                        <input type="checkbox" checked disabled id="essential-cookies">
                        <label for="essential-cookies"></label>
                    </div>
                </div>

                <div class="cookie-option">
                    <div class="cookie-option-info">
                        <strong>Analytics Cookies</strong>
                        <span>Help us understand how visitors interact with our website.</span>
                    </div>
                    <div class="cookie-toggle">
                        <input type="checkbox" id="analytics-cookies">
                        <label for="analytics-cookies"></label>
                    </div>
                </div>

                <div class="cookie-option">
                    <div class="cookie-option-info">
                        <strong>Functional Cookies</strong>
                        <span>Remember your preferences like volume settings.</span>
                    </div>
                    <div class="cookie-toggle">
                        <input type="checkbox" id="functional-cookies">
                        <label for="functional-cookies"></label>
                    </div>
                </div>
            </div>

            <div class="cookie-actions">
                <button id="customize-cookies" class="btn-cookie btn-cookie-outline">Customize</button>
                <button id="essential-only" class="btn-cookie btn-cookie-secondary">Essential Only</button>
                <button id="accept-all" class="btn-cookie btn-cookie-primary">Accept All</button>
            </div>

            <div class="cookie-footer">
                <a href="{{ route('page.show', 'cookie-policy') }}" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Read full Cookie Policy
                </a>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('themes/default/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
