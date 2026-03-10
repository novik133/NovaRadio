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
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">{{ __('frontend.nav.home') }}</a>
                <a href="{{ route('page.show', 'about') }}">{{ __('frontend.nav.about') }}</a>
                <a href="{{ route('schedule') }}">{{ __('frontend.nav.schedule') }}</a>
                <a href="{{ route('team') }}">{{ __('frontend.nav.team') }}</a>
                <a href="{{ route('page.show', 'contact') }}">{{ __('frontend.nav.contact') }}</a>
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
                    <p class="footer-description">{{ __('frontend.footer.description', ['tagline' => $siteTagline ?? __('frontend.hero.tagline')]) }}</p>
                    <div class="footer-social">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <div>
                    <h4 class="footer-title">{{ __('frontend.footer.navigation') }}</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">{{ __('frontend.nav.home') }}</a></li>
                        <li><a href="{{ route('page.show', 'about') }}">{{ __('frontend.nav.about') }}</a></li>
                        <li><a href="{{ route('schedule') }}">{{ __('frontend.nav.schedule') }}</a></li>
                        <li><a href="{{ route('team') }}">{{ __('frontend.nav.team') }}</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="footer-title">{{ __('frontend.footer.legal') }}</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('page.show', 'privacy-policy') }}">{{ __('frontend.footer.privacy_policy') }}</a></li>
                        <li><a href="{{ route('page.show', 'terms-of-service') }}">{{ __('frontend.footer.terms_of_service') }}</a></li>
                        <li><a href="{{ route('page.show', 'cookie-policy') }}">{{ __('frontend.footer.cookie_policy') }}</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="footer-title">{{ __('frontend.footer.contact') }}</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('page.show', 'contact') }}">{{ __('frontend.footer.contact_us') }}</a></li>
                        <li><a href="mailto:contact@novikradio.com">contact@novikradio.com</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} {{ $siteName ?? 'NovaRadio' }}. {{ __('frontend.footer.all_rights_reserved') }}</p>
                <p>{{ __('frontend.footer.made_with_love', ['heart' => '<i class="fas fa-heart" style="color: var(--color-secondary);"></i>']) }}</p>
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
                <h3>{{ __('frontend.cookies.title') }}</h3>
                <p>{{ __('frontend.cookies.description') }}</p>
            </div>

            <div class="cookie-options">
                <div class="cookie-option cookie-option-disabled">
                    <div class="cookie-option-info">
                        <strong>{{ __('frontend.cookies.essential') }}</strong>
                        <span>{{ __('frontend.cookies.essential_desc') }}</span>
                    </div>
                    <div class="cookie-toggle">
                        <input type="checkbox" checked disabled id="essential-cookies">
                        <label for="essential-cookies"></label>
                    </div>
                </div>

                <div class="cookie-option">
                    <div class="cookie-option-info">
                        <strong>{{ __('frontend.cookies.analytics') }}</strong>
                        <span>{{ __('frontend.cookies.analytics_desc') }}</span>
                    </div>
                    <div class="cookie-toggle">
                        <input type="checkbox" id="analytics-cookies">
                        <label for="analytics-cookies"></label>
                    </div>
                </div>

                <div class="cookie-option">
                    <div class="cookie-option-info">
                        <strong>{{ __('frontend.cookies.functional') }}</strong>
                        <span>{{ __('frontend.cookies.functional_desc') }}</span>
                    </div>
                    <div class="cookie-toggle">
                        <input type="checkbox" id="functional-cookies">
                        <label for="functional-cookies"></label>
                    </div>
                </div>
            </div>

            <div class="cookie-actions">
                <button id="customize-cookies" class="btn-cookie btn-cookie-outline">{{ __('frontend.cookies.customize') }}</button>
                <button id="essential-only" class="btn-cookie btn-cookie-secondary">{{ __('frontend.cookies.essential_only') }}</button>
                <button id="accept-all" class="btn-cookie btn-cookie-primary">{{ __('frontend.cookies.accept_all') }}</button>
            </div>

            <div class="cookie-footer">
                <a href="{{ route('page.show', 'cookie-policy') }}" target="_blank">
                    <i class="fas fa-external-link-alt"></i> {{ __('frontend.cookies.read_full_policy') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div class="toast-container" id="toast-container"></div>

    <!-- Scripts -->
    <script src="{{ asset('themes/default/js/app.js') }}"></script>
    <script>
        // Toast Notification System
        window.showToast = function(message, type = 'info', title = null) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            
            const icons = {
                success: 'fa-circle-check',
                error: 'fa-circle-xmark',
                warning: 'fa-triangle-exclamation',
                info: 'fa-circle-info'
            };
            
            const titles = {
                success: title || '{{ __('frontend.toast.success') }}',
                error: title || '{{ __('frontend.toast.error') }}',
                warning: title || '{{ __('frontend.toast.warning') }}',
                info: title || '{{ __('frontend.toast.info') }}'
            };
            
            toast.innerHTML = `
                <i class="fas ${icons[type]} toast-icon"></i>
                <div class="toast-content">
                    <div class="toast-title">${titles[type]}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            toast.addEventListener('click', function(e) {
                if (!e.target.closest('.toast-close')) {
                    toast.classList.add('removing');
                    setTimeout(() => toast.remove(), 300);
                }
            });
            
            container.appendChild(toast);
            
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.classList.add('removing');
                    setTimeout(() => toast.remove(), 300);
                }
            }, 5000);
        };
        
        window.alert = function(message) {
            window.showToast(message, 'info');
        };
        
        @if(session('success'))
            window.showToast('{{ session('success') }}', 'success');
        @endif
        
        @if(session('error'))
            window.showToast('{{ session('error') }}', 'error');
        @endif
        
        @if(session('warning'))
            window.showToast('{{ session('warning') }}', 'warning');
        @endif
        
        @if(session('info'))
            window.showToast('{{ session('info') }}', 'info');
        @endif
    </script>
    @stack('scripts')
</body>
</html>
