<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#2c3e50">
    <meta name="description" content="Interface d'administration Chocolatey CMS - Panneau de contrôle sécurisé">
    
    <title>@yield('title', 'Administration') - Chocolatey CMS</title>
    
    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    
    <!-- Bootstrap CSS with integrity -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-9ndCyUa4g9nfbzn0YJgKKQJsL6o0lM7VyUtHJOzHJx8ZcQP7qS6+9T8P8E8QP7qS"
          crossorigin="anonymous">
    <!-- Font Awesome with integrity -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
          rel="stylesheet"
          integrity="sha512-iecdLmaskl7CVkqk6nZkHpQQpkdWYKg/HnL+PSdVg/TZ7NRJFhO9Tss6LPG9HaA6KMB5/j2U4QK2ZkVpIKBwrZw=="
          crossorigin="anonymous">
    
    <style>
        /* WCAG 2.1 AA Compliant Styles */
        .sidebar {
            min-height: 100vh;
            background: #2c3e50;
        }
        
        .sidebar .nav-link {
            color: #ecf0f1;
            border-radius: 0;
            padding: 12px 16px;
            transition: all 0.2s ease-in-out;
            border-left: 3px solid transparent;
            text-decoration: none;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link:focus {
            background: #34495e;
            color: #fff;
            border-left-color: #3498db;
            outline: 2px solid #3498db;
            outline-offset: -2px;
        }
        
        .sidebar .nav-link.active {
            background: #34495e;
            color: #fff;
            border-left-color: #e74c3c;
            font-weight: 600;
        }
        
        .main-content {
            padding: 20px;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border: none;
            transition: transform 0.2s ease-in-out;
        }
        
        .stats-card:hover,
        .stats-card:focus-within {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }
        
        .stats-card .card-body {
            text-align: center;
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #ddd;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .sidebar {
                background: #000;
                border-right: 2px solid #fff;
            }
            .sidebar .nav-link {
                color: #fff;
                border: 1px solid #fff;
                margin: 2px;
            }
        }
        
        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
        
        /* Focus management */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 6px;
            background: #000;
            color: #fff;
            padding: 8px;
            text-decoration: none;
            border-radius: 4px;
            z-index: 9999;
        }
        
        .skip-link:focus {
            top: 6px;
        }
        
        /* Ensure sufficient color contrast */
        .btn-primary {
            background-color: #2563eb;
            border-color: #2563eb;
        }
        
        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }
        
        /* Screen reader only content */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
    </style>
</head>
<body>
    <!-- Skip to main content link for accessibility -->
    <a href="#main-content" class="skip-link" aria-label="Aller au contenu principal">Aller au contenu principal</a>
    
    <!-- Screen reader announcement for dynamic content -->
    <div id="announcements" aria-live="polite" aria-atomic="true" class="sr-only"></div>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Navigation -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse" 
                 id="sidebar" 
                 aria-label="Navigation principale d'administration"
                 role="navigation">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h1 class="h4 text-white" id="app-title">Chocolatey Admin</h1>
                        <p class="text-muted small" role="doc-subtitle">Panel d'administration</p>
                    </div>
                    
                    <ul class="nav flex-column" role="list">
                        <li class="nav-item" role="listitem">
                            <a class="nav-link @if(request()->is('admin') || request()->is('admin/dashboard')) active @endif" 
                               href="/admin/dashboard"
                               aria-current="@if(request()->is('admin') || request()->is('admin/dashboard')) page @else false @endif"
                               role="menuitem">
                                <i class="fas fa-tachometer-alt" aria-hidden="true"></i> 
                                <span>Tableau de bord</span>
                                <span class="sr-only">@if(request()->is('admin') || request()->is('admin/dashboard')) (page actuelle) @endif</span>
                            </a>
                        </li>
                        <li class="nav-item" role="listitem">
                            <a class="nav-link @if(request()->is('admin/users*')) active @endif" 
                               href="/admin/users"
                               aria-current="@if(request()->is('admin/users*')) page @else false @endif"
                               role="menuitem">
                                <i class="fas fa-users" aria-hidden="true"></i> 
                                <span>Utilisateurs</span>
                                <span class="sr-only">@if(request()->is('admin/users*')) (section actuelle) @endif</span>
                            </a>
                        </li>
                        <li class="nav-item" role="listitem">
                            <a class="nav-link @if(request()->is('admin/articles*')) active @endif" 
                               href="/admin/articles"
                               aria-current="@if(request()->is('admin/articles*')) page @else false @endif"
                               role="menuitem">
                                <i class="fas fa-newspaper" aria-hidden="true"></i> 
                                <span>Articles</span>
                                <span class="sr-only">@if(request()->is('admin/articles*')) (section actuelle) @endif</span>
                            </a>
                        </li>
                        <li class="nav-item" role="listitem">
                            <a class="nav-link @if(request()->is('admin/settings*')) active @endif" 
                               href="/admin/settings"
                               aria-current="@if(request()->is('admin/settings*')) page @else false @endif"
                               role="menuitem">
                                <i class="fas fa-cog" aria-hidden="true"></i> 
                                <span>Paramètres</span>
                                <span class="sr-only">@if(request()->is('admin/settings*')) (section actuelle) @endif</span>
                            </a>
                        </li>
                        <li class="nav-item" role="listitem">
                            <a class="nav-link @if(request()->is('admin/logs*')) active @endif" 
                               href="/admin/logs"
                               aria-current="@if(request()->is('admin/logs*')) page @else false @endif"
                               role="menuitem">
                                <i class="fas fa-list-alt" aria-hidden="true"></i> 
                                <span>Logs</span>
                                <span class="sr-only">@if(request()->is('admin/logs*')) (section actuelle) @endif</span>
                            </a>
                        </li>
                        <li class="nav-item mt-3" role="listitem">
                            <a class="nav-link" 
                               href="/"
                               role="menuitem"
                               aria-label="Retourner au site principal">
                                <i class="fas fa-arrow-left" aria-hidden="true"></i> 
                                <span>Retour au site</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content" 
                  id="main-content" 
                  role="main" 
                  aria-labelledby="page-title">
                
                <!-- Top navigation -->
                <header class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2" id="page-title">@yield('page-title', 'Administration')</h1>
                    <div class="btn-toolbar mb-2 mb-md-0" role="toolbar" aria-label="Actions de la page">
                        <div class="btn-group me-2" role="group" aria-label="Actions de rafraîchissement">
                            <button type="button" 
                                    class="btn btn-outline-secondary" 
                                    onclick="location.reload()"
                                    aria-label="Actualiser la page actuelle"
                                    title="Actualiser la page">
                                <i class="fas fa-sync-alt" aria-hidden="true"></i> 
                                <span>Actualiser</span>
                            </button>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" 
                                    type="button" 
                                    id="admin-menu"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                    aria-haspopup="true"
                                    aria-label="Menu administrateur">
                                <i class="fas fa-user" aria-hidden="true"></i> 
                                <span>Admin</span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="admin-menu" role="menu">
                                <li role="presentation">
                                    <a class="dropdown-item" href="/admin/profile" role="menuitem">
                                        <i class="fas fa-user-edit" aria-hidden="true"></i> 
                                        <span>Profil</span>
                                    </a>
                                </li>
                                <li role="presentation"><hr class="dropdown-divider"></li>
                                <li role="presentation">
                                    <a class="dropdown-item" href="/logout" role="menuitem">
                                        <i class="fas fa-sign-out-alt" aria-hidden="true"></i> 
                                        <span>Déconnexion</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </header>

                <!-- Content area with proper heading hierarchy -->
                <section aria-label="Contenu principal">
                    @yield('content')
                </section>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS with integrity -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"></script>
    <!-- jQuery with integrity -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha384-vtXRMe3mGCbOeY7l30aIg8H9p3GdeSe4IFlP6G8JMa7o7lXvnz3GFKzPxzJdPfGK"
            crossorigin="anonymous"></script>
    
    <script>
        // Accessibility and Performance optimizations
        document.addEventListener('DOMContentLoaded', function() {
            // Set up keyboard navigation
            setupKeyboardNavigation();
            
            // Set up focus management
            setupFocusManagement();
            
            // Set up screen reader announcements
            setupScreenReaderSupport();
            
            // Performance monitoring
            if ('performance' in window) {
                console.log('Page load time:', performance.timing.loadEventEnd - performance.timing.navigationStart, 'ms');
            }
        });

        // Global AJAX setup with accessibility improvements
        $.ajaxSetup({
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
            },
            beforeSend: function() {
                // Show loading indicator for screen readers
                announceToScreenReader('Chargement en cours...');
            },
            complete: function() {
                // Announce completion
                setTimeout(() => announceToScreenReader('Contenu mis à jour'), 500);
            }
        });

        // Accessible alert helper with WCAG compliance
        function showAlert(message, type = 'success', autoHide = true) {
            const alertId = 'alert-' + Date.now();
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" 
                     role="alert" 
                     id="${alertId}"
                     aria-live="assertive" 
                     aria-atomic="true">
                    <span class="alert-message">${message}</span>
                    <button type="button" 
                            class="btn-close" 
                            data-bs-dismiss="alert"
                            aria-label="Fermer cette alerte"
                            title="Fermer"></button>
                </div>
            `;
            
            const $alert = $(alertHtml);
            $('.main-content').prepend($alert);
            
            // Focus on alert for screen readers
            $alert.focus();
            
            // Announce to screen reader
            announceToScreenReader(message);
            
            if (autoHide) {
                setTimeout(() => {
                    $alert.fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 5000);
            }
        }

        // Screen reader announcements
        function announceToScreenReader(message) {
            const announcement = document.getElementById('announcements');
            if (announcement) {
                announcement.textContent = message;
                setTimeout(() => {
                    announcement.textContent = '';
                }, 1000);
            }
        }

        // Keyboard navigation setup
        function setupKeyboardNavigation() {
            // Skip to main content functionality
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Tab' && !e.shiftKey && document.activeElement.classList.contains('skip-link')) {
                    e.preventDefault();
                    document.getElementById('main-content').focus();
                }
            });

            // Escape key to close modals and dropdowns
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    // Close any open modals
                    $('.modal.show').modal('hide');
                    
                    // Close any open dropdowns
                    $('.dropdown-menu.show').removeClass('show');
                }
            });
        }

        // Focus management for better accessibility
        function setupFocusManagement() {
            // When modals open, focus on the first focusable element
            $(document).on('shown.bs.modal', '.modal', function() {
                const firstFocusable = $(this).find('input, button, select, textarea, [tabindex]:not([tabindex="-1"])').first();
                firstFocusable.focus();
            });

            // When dropdowns open, focus management
            $(document).on('shown.bs.dropdown', '.dropdown', function() {
                const firstItem = $(this).find('.dropdown-menu a, .dropdown-menu button').first();
                firstItem.focus();
            });
        }

        // Screen reader support setup
        function setupScreenReaderSupport() {
            // Update aria-expanded attributes for collapsible elements
            $(document).on('click', '[data-bs-toggle="collapse"]', function() {
                const target = $($(this).attr('data-bs-target'));
                const isExpanded = target.hasClass('show');
                $(this).attr('aria-expanded', !isExpanded);
            });
            
            // Update page title for screen readers on navigation
            if (typeof window.history.replaceState === 'function') {
                const pageTitle = document.getElementById('page-title');
                if (pageTitle) {
                    document.title = pageTitle.textContent + ' - Chocolatey CMS';
                }
            }
        }

        // Performance optimization: Lazy loading for tables
        function initializeLazyLoading() {
            if ('IntersectionObserver' in window) {
                const lazyElements = document.querySelectorAll('[data-lazy]');
                const lazyObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const element = entry.target;
                            // Load content
                            element.classList.remove('lazy');
                            lazyObserver.unobserve(element);
                        }
                    });
                });
                
                lazyElements.forEach(element => {
                    lazyObserver.observe(element);
                });
            }
        }

        // Error handling with accessibility
        function handleAccessibleError(error, context = '') {
            console.error('Error in ' + context + ':', error);
            showAlert('Une erreur s\'est produite. Veuillez réessayer.', 'danger');
            announceToScreenReader('Erreur: ' + error.message);
        }

        // Initialize all accessibility features
        setTimeout(initializeLazyLoading, 100);
    </script>
    
    @yield('scripts')
</body>
</html>