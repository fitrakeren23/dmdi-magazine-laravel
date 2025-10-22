@extends('layouts.frontend')

@section('title', $locale == 'id' ? 'Beranda - DMDI Magazine' : 'Home - DMDI Magazine')

@section('meta')
<meta name="description" content="{{ $locale == 'id' ? 'DMDI Magazine - Media terpercaya untuk berita dan informasi terkini dalam Bahasa Indonesia dan English' : 'DMDI Magazine - Trusted media for the latest news and information in Indonesian and English' }}">
<meta property="og:title" content="{{ $locale == 'id' ? 'Beranda - DMDI Magazine' : 'Home - DMDI Magazine' }}">
<meta property="og:description" content="{{ $locale == 'id' ? 'Media terpercaya untuk berita dan informasi terkini' : 'Trusted media for the latest news and information' }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta name="twitter:card" content="summary_large_image">
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-section py-5">
    <div class="container">
        @if($featuredArticles->count() > 0)
        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Main Featured Article -->
                @php $mainFeatured = $featuredArticles->first(); @endphp
                <div class="main-featured-article position-relative">
                    <a href="{{ url($locale . '/article/' . $mainFeatured->slug) }}" class="text-decoration-none text-dark">
                        @if($mainFeatured->featured_image)
                        <div class="featured-image mb-3 overflow-hidden">
                            <img src="{{ asset('storage/' . $mainFeatured->featured_image) }}" 
                                 alt="{{ $locale == 'id' ? $mainFeatured->title_id : $mainFeatured->title_en }}" 
                                 class="img-fluid w-100" style="height: 500px; object-fit: cover;">
                        </div>
                        @else
                        <div class="featured-image-placeholder mb-3 bg-light d-flex align-items-center justify-content-center" style="height: 500px;">
                            <i class="bi bi-image text-secondary" style="font-size: 4rem;"></i>
                        </div>
                        @endif
                        
                        <div class="article-meta mb-3">
                            <span class="badge bg-dark text-uppercase me-2">{{ $locale == 'id' ? 'Unggulan' : 'Featured' }}</span>
                            <span class="text-muted small">
                                {{ $mainFeatured->created_at->format('M d, Y') }}
                            </span>
                            <span class="text-muted small ms-2">
                                <i class="bi bi-dot"></i>
                            </span>
                            <span class="text-muted small">
                                {{ $mainFeatured->author }}
                            </span>
                        </div>
                        
                        <h1 class="article-title display-5 mb-3" style="font-size: 2.5rem;">
                            {{ $locale == 'id' ? $mainFeatured->title_id : $mainFeatured->title_en }}
                        </h1>
                        
                        <p class="lead text-muted mb-0">
                            {{ $locale == 'id' ? $mainFeatured->excerpt_id : $mainFeatured->excerpt_en }}
                        </p>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Ad Space - Top -->
                <div class="ad-space mb-4 bg-light border text-center p-4">
                    <small class="text-muted d-block mb-2">{{ $locale == 'id' ? 'IKLAN' : 'ADVERTISEMENT' }}</small>
                    <div class="bg-white border p-4" style="min-height: 250px;">
                        <p class="text-muted mb-0">300 x 250</p>
                    </div>
                </div>
                
                <!-- Side Featured Articles -->
                <div class="side-featured-articles">
                    <h4 class="mb-4 pb-2" style="border-bottom: 3px solid var(--accent-color); display: inline-block;">
                        {{ $locale == 'id' ? 'Artikel Unggulan' : 'Featured Articles' }}
                    </h4>
                    
                    @foreach($featuredArticles->skip(1)->take(3) as $article)
                    <div class="side-article mb-4 pb-4 border-bottom">
                        <a href="{{ url($locale . '/article/' . $article->slug) }}" class="text-decoration-none text-dark d-flex gap-3">
                            @if($article->featured_image)
                            <div class="side-image flex-shrink-0" style="width: 100px; height: 100px;">
                                <img src="{{ asset('storage/' . $article->featured_image) }}" 
                                     alt="{{ $locale == 'id' ? $article->title_id : $article->title_en }}" 
                                     class="img-fluid w-100 h-100" style="object-fit: cover;">
                            </div>
                            @else
                            <div class="side-image-placeholder flex-shrink-0 bg-light d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                <i class="bi bi-image text-secondary"></i>
                            </div>
                            @endif
                            
                            <div class="flex-grow-1">
                                <h6 class="article-title mb-2" style="font-size: 0.95rem; line-height: 1.4;">
                                    {{ $locale == 'id' ? $article->title_id : $article->title_en }}
                                </h6>
                                
                                <div class="article-meta">
                                    <span class="text-muted small">
                                        {{ $article->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-newspaper text-muted" style="font-size: 4rem;"></i>
            <h4 class="text-muted mt-3">{{ $locale == 'id' ? 'Belum ada artikel unggulan' : 'No featured articles yet' }}</h4>
        </div>
        @endif
    </div>
</section>

<!-- Latest Articles Section -->
<section class="latest-articles py-5 bg-light">
    <div class="container">
        <div class="section-header mb-5 text-center">
            <h2 class="section-title mb-3" style="font-size: 2.5rem;">
                {{ $locale == 'id' ? 'Artikel Terbaru' : 'Latest Articles' }}
            </h2>
            <p class="text-muted">
                {{ $locale == 'id' ? 'Kumpulan artikel terbaru dari DMDI Magazine' : 'Latest articles from DMDI Magazine' }}
            </p>
        </div>
        
        <div class="row g-4">
            @foreach($latestArticles as $article)
            <div class="col-md-6 col-lg-4 mb-4">
                <article class="article-card card h-100 shadow-sm">
                    <a href="{{ url($locale . '/article/' . $article->slug) }}" class="text-decoration-none text-dark">
                        @if($article->featured_image)
                        <div class="card-img-top overflow-hidden">
                            <img src="{{ asset('storage/' . $article->featured_image) }}" 
                                 alt="{{ $locale == 'id' ? $article->title_id : $article->title_en }}" 
                                 class="img-fluid w-100" style="height: 240px; object-fit: cover;">
                        </div>
                        @else
                        <div class="card-img-placeholder bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 240px;">
                            <i class="bi bi-image text-secondary" style="font-size: 3rem;"></i>
                        </div>
                        @endif
                        
                        <div class="card-body p-4">
                            <div class="article-meta mb-3">
                                <span class="badge bg-secondary bg-opacity-10 text-dark text-uppercase small">
                                    {{ $article->category->name_id ?? 'Umum' }}
                                </span>
                            </div>
                            
                            <h3 class="article-title h5 mb-3" style="line-height: 1.4; min-height: 3rem;">
                                {{ \Illuminate\Support\Str::limit($locale == 'id' ? $article->title_id : $article->title_en, 80) }}
                            </h3>
                            
                            <p class="card-text text-muted mb-3" style="font-size: 0.9rem;">
                                {{ \Illuminate\Support\Str::limit($locale == 'id' ? $article->excerpt_id : $article->excerpt_en, 100) }}
                            </p>
                            
                            <div class="article-meta mt-auto pt-3 border-top">
                                <span class="text-muted small">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $article->created_at->format('M d, Y') }}
                                </span>
                                <span class="text-muted small ms-3">
                                    <i class="bi bi-person me-1"></i>
                                    {{ $article->author }}
                                </span>
                            </div>
                        </div>
                    </a>
                </article>
            </div>
            @endforeach
        </div>
        
        @if($latestArticles->count() == 0)
        <div class="text-center py-5">
            <i class="bi bi-newspaper text-muted" style="font-size: 4rem;"></i>
            <h4 class="text-muted mt-3">{{ $locale == 'id' ? 'Belum ada artikel' : 'No articles yet' }}</h4>
            <p class="text-muted">{{ $locale == 'id' ? 'Silakan buat artikel pertama melalui admin panel' : 'Please create your first article through admin panel' }}</p>
        </div>
        @endif
    </div>
</section>

<style>

    
</style>
@endsection