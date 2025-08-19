<!DOCTYPE html>
<html lang="{{ $chocolatey->siteLanguage ?? 'fr' }}" ng-app="app">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="NOODP">
    <meta name="theme-color" content="#1e7cf7">
    
    <!-- Performance optimizations -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="dns-prefetch" href="//{{ parse_url($chocolatey->hotelUrl, PHP_URL_HOST) }}">
    
    <title>{{ $chocolatey->hotelName }} - Virtual World & Avatar Chat</title>
    
    <!-- SEO and Accessibility -->
    <meta name="description" content="Rejoignez des millions d'utilisateurs dans le monde virtuel le plus populaire. Créez votre avatar, rencontrez de nouveaux amis, jouez des rôles et construisez des espaces incroyables.">
    <meta name="keywords" content="habbo,virtual world,avatar,chat,jeux,social,amis">
    <meta name="author" content="{{ $chocolatey->hotelName }}">
    
    <!-- Open Graph optimized -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $chocolatey->hotelName }}">
    <meta property="og:title" content="{{ $chocolatey->hotelName }} - Monde Virtuel">
    <meta property="og:description" content="Rejoignez des millions d'utilisateurs dans le monde virtuel le plus populaire. Créez votre avatar, rencontrez de nouveaux amis.">
    <meta property="og:url" content="{{ $chocolatey->hotelUrl }}">
    <meta property="og:image" content="{{ $chocolatey->hotelUrl }}habbo-web/assets/images/og-image.png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="628">
    <meta property="og:image:alt" content="Aperçu de {{ $chocolatey->hotelName }}">
    <meta property="og:locale" content="{{ str_replace('-', '_', $chocolatey->siteLanguage) }}">
    
    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $chocolatey->hotelName }} - Monde Virtuel">
    <meta name="twitter:description" content="Rejoignez des millions d'utilisateurs dans le monde virtuel le plus populaire.">
    <meta name="twitter:site" content="{{ $chocolatey->twitter->title ?? '@habbo' }}">
    <meta name="twitter:image" content="{{ $chocolatey->hotelUrl }}habbo-web/assets/images/og-image.png">
    <meta name="twitter:image:alt" content="Aperçu de {{ $chocolatey->hotelName }}">
    
    <!-- Technical meta -->
    <meta name="fragment" content="!">
    <meta name="revision" content="{{ config('app.version', 'f05e1ca') }}">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=5">
    
    <!-- Icons and favicons -->
    <link rel="icon" type="image/x-icon" href="{{ $chocolatey->hotelUrl }}habbo-web/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ $chocolatey->hotelUrl }}habbo-web/apple-touch-icon.png">
    
    <!-- Styles with performance optimization -->
    <link rel="stylesheet" href="{{ $chocolatey->hotelUrl }}habbo-web/app.css?v={{ config('app.version', '1.0') }}">
    
    <!-- Critical CSS inline for performance -->
    <style>
        /* Critical CSS for above-the-fold content */
        body { margin: 0; font-family: Ubuntu, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .content { min-height: 100vh; }
        [ng-cloak] { display: none !important; }
        
        /* Accessibility improvements */
        .sr-only {
            position: absolute !important;
            width: 1px !important;
            height: 1px !important;
            padding: 0 !important;
            margin: -1px !important;
            overflow: hidden !important;
            clip: rect(0, 0, 0, 0) !important;
            white-space: nowrap !important;
            border: 0 !important;
        }
        
        /* Focus improvements */
        *:focus {
            outline: 2px solid #1e7cf7;
            outline-offset: 2px;
        }
        
        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
    
    <!-- Fonts with performance optimization -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&family=Ubuntu+Condensed&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&family=Ubuntu+Condensed&display=swap"></noscript>
    
    <!-- Configuration JSON-LD for better SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "{{ $chocolatey->hotelName }}",
        "url": "{{ $chocolatey->hotelUrl }}",
        "description": "Monde virtuel interactif avec avatars et chat",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "{{ $chocolatey->hotelUrl }}search?q={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>
    
    <!-- Application configuration with CSP compliance -->
    <script nonce="{{ csp_nonce() ?? '' }}">
        // Secure global configuration
        window.session = @json($user ?? null);
        window.chocolatey = {
            captcha: @json($chocolatey->recaptcha ?? ''),
            facebook: @json($chocolatey->facebook->app->key ?? ''),
            url: @json($chocolatey->hotelUrl ?? ''),
            base: @json($chocolatey->path ?? ''),
            earn: @json(($chocolatey->earn ?? 0) == 1),
            name: @json($chocolatey->hotelName ?? 'Habbo'),
            lang: @json($chocolatey->siteLanguage ?? 'fr'),
            album: @json($chocolatey->badgeRepository ?? ''),
            plang: @json($chocolatey->pageLanguage ?? 'fr')
        };
        window.geoLocation = @json($chocolatey->location ?? (object)[]);
        window.partnerCodeInfo = null;
        window.banner = null;
        
        // Performance monitoring
        if ('performance' in window && 'mark' in performance) {
            performance.mark('app-config-loaded');
        }
    </script>
</head>

<body ng-cloak>
    <!-- Skip link for accessibility -->
    <a href="#main-content" class="sr-only">Aller au contenu principal</a>
    
    <!-- Main application container -->
    <div id="main-content" class="content" ui-view role="main" aria-label="Application principale">
        <!-- Loading state for screen readers -->
        <div class="sr-only" aria-live="polite" id="loading-status">Chargement de l'application...</div>
    </div>
    
    <!-- Footer -->
    <footer role="contentinfo">
        <habbo-footer></habbo-footer>
    </footer>
    
    <!-- Client container -->
    <div habbo-require-session role="application" aria-label="Client Habbo">
        <habbo-client></habbo-client>
    </div>
    
    <!-- Cookie banner with proper ARIA -->
    <div habbo-require-no-session role="banner" aria-label="Informations sur les cookies">
        <habbo-eu-cookie-banner></habbo-eu-cookie-banner>
    </div>
    
    <!-- Scripts with performance optimization -->
    <script src="{{ $chocolatey->hotelUrl }}habbo-web/vendor.js?v={{ config('app.version', '1.0') }}" defer></script>
    <script src="{{ $chocolatey->hotelUrl }}habbo-web/scripts.js?v={{ config('app.version', '1.0') }}" defer></script>
    
    <!-- Performance and error tracking -->
    <script nonce="{{ csp_nonce() ?? '' }}">
        // Basic performance tracking
        window.addEventListener('load', function() {
            if ('performance' in window) {
                setTimeout(function() {
                    const perfData = performance.timing;
                    console.log('Page Load Time:', perfData.loadEventEnd - perfData.navigationStart, 'ms');
                }, 0);
            }
            
            // Remove loading message for screen readers
            const loadingStatus = document.getElementById('loading-status');
            if (loadingStatus) {
                loadingStatus.textContent = 'Application chargée';
            }
        });
        
        // Error tracking for accessibility
        window.addEventListener('error', function(e) {
            console.error('Application error:', e.error);
        });
    </script>
</body>
</html>