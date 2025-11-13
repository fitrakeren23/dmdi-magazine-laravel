<!DOCTYPE html>
<html lang="{{ app()->getLocale() ?? 'id' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DMDI Magazine') - Dunia Melayu Dunia Islam Indonesia</title>
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts seperti Esquire -->
    <link href="https://fonts.googleapis.com/css2?family=Libre+Franklin:wght@300;400;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        /* Esquire-like Typography */
        body {
            font-family: 'Libre Franklin', sans-serif;
            line-height: 1.6;
            color: #333;
        }
        
        .article-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 1rem;
        }
        
        .article-excerpt {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 1.5rem;
        }
        
        /* Header Style seperti Esquire */
        .navbar {
            border-bottom: 1px solid #e5e5e5;
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        /* Advertisement Spots */
        .ad-spot {
            background: #f8f9fa;
            padding: 2rem;
            text-align: center;
            margin: 2rem 0;
            border: 1px dashed #dee2e6;
        }
        
        /* Article Grid */
        .article-card {
            transition: transform 0.2s;
        }
        
        .article-card:hover {
            transform: translateY(-5px);
        }
        
        /* Language Switcher */
        .language-switcher a {
            text-decoration: none;
            margin: 0 5px;
        }
        
        .language-switcher a.active {
            font-weight: bold;
            color: #000;
        }
    </style>
    
    @stack('styles')
</head>
<body>
<!-- Header -->
<header class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand fw-black fs-2 text-dark text-decoration-none" href="{{ url('/' . (app()->getLocale() ?? 'id')) }}">
            DMDI
        </a>
        
        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navigation -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item px-3">
                    <a class="nav-link text-dark fw-semibold text-uppercase small" href="{{ url('/' . (app()->getLocale() ?? 'id')) }}">
                        Beranda
                    </a>
                </li>
                <li class="nav-item px-3">
                    <a class="nav-link text-dark fw-semibold text-uppercase small" href="#">
                        Kategori
                    </a>
                </li>
                <li class="nav-item px-3">
                    <a class="nav-link text-dark fw-semibold text-uppercase small" href="#">
                        Tentang
                    </a>
                </li>
            </ul>
            
            <!-- Language Switcher (partial) -->
            <div class="language-switcher d-flex align-items-center">
                @include('layouts.partials.lang-toggle')
                <span class="text-muted mx-2">|</span>
                {{-- optionally show both buttons --}}
                <span class="d-none d-md-inline ms-2 small text-muted">Switch language</span>
            </div>
        </div>
    </div>
</header>

    <main>
        @yield('content')
    </main>

 <!-- Footer -->
<footer class="bg-dark text-white py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h5 class="fw-bold mb-3">DMDI MAGAZINE</h5>
                <p class="mb-3 opacity-75">Dunia Melayu Dunia Islam Indonesia</p>
                <p class="small opacity-50">
                    &copy; {{ date('Y') }} DMDI Magazine. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</footer>
    <!-- Bootstrap & jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>