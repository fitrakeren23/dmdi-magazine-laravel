<!DOCTYPE html>
<html lang="{{ app()->getLocale() ?? 'id' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DMDI Magazine')</title>
    
    @yield('meta')
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts - Mirip Esquire -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #1a1a1a;
            --secondary-color: #666;
            --accent-color: #c9a961;
            --bg-light: #fafafa;
            --border-color: #e5e5e5;
        }
        /* ... (styling unchanged) ... */
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between py-3">
                <a href="{{ url('/' . (app()->getLocale() ?? 'id')) }}" class="logo">
                    DMDI
                </a>
                
                <button class="mobile-menu-toggle" id="mobileMenuToggle">
                    <i class="bi bi-list"></i>
                </button>
                
                <nav class="main-nav" id="mainNav">
                    <a href="{{ url('/' . (app()->getLocale() ?? 'id')) }}" class="nav-link">
                        {{ app()->getLocale() == 'id' ? 'HOME' : 'HOME' }}
                    </a>
                    <a href="{{ url('/' . (app()->getLocale() ?? 'id') . '#politics') }}" class="nav-link">
                        {{ app()->getLocale() == 'id' ? 'POLITIK' : 'POLITICS' }}
                    </a>
                    <a href="{{ url('/' . (app()->getLocale() ?? 'id') . '#culture') }}" class="nav-link">
                        {{ app()->getLocale() == 'id' ? 'BUDAYA' : 'CULTURE' }}
                    </a>
                    <a href="{{ url('/' . (app()->getLocale() ?? 'id') . '#lifestyle') }}" class="nav-link">
                        {{ app()->getLocale() == 'id' ? 'GAYA HIDUP' : 'LIFESTYLE' }}
                    </a>
                    
                    <div class="lang-switcher">
                        @include('layouts.partials.lang-toggle')
                    </div>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer (unchanged content) -->
    <footer class="bg-dark text-light py-5 mt-5">
        <!-- ... footer content unchanged ... -->
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Mobile menu toggle (unchanged)
        document.getElementById('mobileMenuToggle')?.addEventListener('click', function() {
            document.getElementById('mainNav')?.classList.toggle('active');
        });
    </script>
    
    @stack('scripts')
</body>
</html>