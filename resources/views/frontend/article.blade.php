@extends('layouts.frontend')

@section('title', ($locale == 'id' ? $article->title_id : $article->title_en) . ' - DMDI Magazine')

@section('meta')
<meta name="description" content="{{ \Illuminate\Support\Str::limit($locale == 'id' ? $article->excerpt_id : $article->excerpt_en, 160) }}">
<meta name="author" content="{{ $article->author }}">
<meta name="keywords" content="{{ $article->category->name_id ?? 'artikel' }}, DMDI Magazine, {{ $article->author }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="article">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $locale == 'id' ? $article->title_id : $article->title_en }}">
<meta property="og:description" content="{{ \Illuminate\Support\Str::limit($locale == 'id' ? $article->excerpt_id : $article->excerpt_en, 160) }}">
@if($article->featured_image)
<meta property="og:image" content="{{ asset('storage/' . $article->featured_image) }}">
@endif
<meta property="article:published_time" content="{{ $article->created_at->toIso8601String() }}">
<meta property="article:author" content="{{ $article->author }}">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ url()->current() }}">
<meta name="twitter:title" content="{{ $locale == 'id' ? $article->title_id : $article->title_en }}">
<meta name="twitter:description" content="{{ \Illuminate\Support\Str::limit($locale == 'id' ? $article->excerpt_id : $article->excerpt_en, 160) }}">
@if($article->featured_image)
<meta name="twitter:image" content="{{ asset('storage/' . $article->featured_image) }}">
@endif

<!-- Schema.org structured data -->
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'NewsArticle',
    'headline' => $locale == 'id' ? $article->title_id : $article->title_en,
    'description' => $locale == 'id' ? $article->excerpt_id : $article->excerpt_en,
    'author' => [
        '@type' => 'Person',
        'name' => $article->author
    ],
    'datePublished' => $article->created_at->toIso8601String(),
    'dateModified' => $article->updated_at->toIso8601String(),
    'image' => $article->featured_image ? asset('storage/' . $article->featured_image) : null,
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'DMDI Magazine',
        'logo' => [
            '@type' => 'ImageObject',
            'url' => url('/') . '/logo.png'
        ]
    ]
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endsection

@section('content')
<div class="article-detail-page py-4">
    <div class="container">
        <div class="row g-4">
            <!-- Main Article Content -->
            <div class="col-lg-8">
                <article class="main-article">
                    <!-- Article Header -->
                    <header class="article-header mb-5">
                        <!-- Category Badge -->
                        <div class="category-badge mb-3">
                            <span class="badge text-uppercase fw-semibold" style="background: var(--accent-color); color: #1a1a1a; padding: 0.5rem 1rem; font-size: 0.75rem; letter-spacing: 1px;">
                                {{ $locale == 'id' ? ($article->category->name_id ?? 'Umum') : ($article->category->name_en ?? 'General') }}
                            </span>
                        </div>
                        
                        <!-- Article Title -->
                        <h1 class="article-title mb-4" style="font-size: 3rem; line-height: 1.2; font-weight: 700;">
                            {{ $locale == 'id' ? $article->title_id : $article->title_en }}
                        </h1>
                        
                        <!-- Article Meta -->
                        <div class="article-meta mb-4 pb-4 border-bottom">
                            <div class="d-flex flex-wrap align-items-center text-muted gap-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-circle me-2" style="font-size: 1.2rem;"></i>
                                    <span class="fw-semibold">{{ $article->author }}</span>
                                </div>
                                <span class="text-muted">|</span>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar3 me-2"></i>
                                    <span>{{ $article->created_at->format('F d, Y') }}</span>
                                </div>
                                <span class="text-muted">|</span>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-eye me-2"></i>
                                    <span>{{ $article->view_count ?? rand(100, 1000) }} {{ $locale == 'id' ? 'views' : 'views' }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Featured Image -->
                        @if($article->featured_image)
                        <div class="featured-image mb-5">
                            <img src="{{ asset('storage/' . $article->featured_image) }}" 
                                 alt="{{ $locale == 'id' ? $article->title_id : $article->title_en }}" 
                                 class="img-fluid w-100" style="max-height: 600px; object-fit: cover;">
                            @if($article->featured_image_caption ?? false)
                            <div class="image-caption text-center text-muted mt-3 small fst-italic">
                                {{ $article->featured_image_caption }}
                            </div>
                            @endif
                        </div>
                        @endif
                        
                        <!-- Article Excerpt -->
                        <div class="article-excerpt mb-4">
                            <p class="lead text-muted" style="font-size: 1.25rem; line-height: 1.7;">
                                {{ $locale == 'id' ? $article->excerpt_id : $article->excerpt_en }}
                            </p>
                        </div>
                    </header>

                    <!-- Article Content -->
                    <div class="article-content">
                        <div class="content-body">
                            {!! $locale == 'id' ? $article->content_id : $article->content_en !!}
                        </div>
                    </div>

                    <!-- Article Footer -->
                    <footer class="article-footer mt-5 pt-4 border-top">
                        <!-- Tags -->
                        <div class="article-tags mb-4">
                            <strong class="me-2">{{ $locale == 'id' ? 'Tags:' : 'Tags:' }}</strong>
                            <span class="badge bg-light text-dark me-2">#{{ $article->category->name_id ?? 'News' }}</span>
                            <span class="badge bg-light text-dark me-2">#{{ $article->author }}</span>
                        </div>
                        
                        <!-- Share Buttons -->
                        <div class="share-buttons mb-4">
                            <strong class="me-3">{{ $locale == 'id' ? 'Bagikan:' : 'Share:' }}</strong>
                            <button class="btn btn-outline-secondary btn-sm me-2">
                                <i class="bi bi-facebook"></i> Facebook
                            </button>
                            <button class="btn btn-outline-secondary btn-sm me-2">
                                <i class="bi bi-twitter"></i> Twitter
                            </button>
                            <button class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-link-45deg"></i> Copy Link
                            </button>
                        </div>
                    </footer>
                </article>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <aside class="sidebar position-sticky" style="top: 100px;">
                    <!-- Ad Space - Top -->
                    <div class="sidebar-widget mb-4">
                        <div class="ad-space bg-light border text-center p-3">
                            <small class="text-muted d-block mb-2">{{ $locale == 'id' ? 'IKLAN' : 'ADVERTISEMENT' }}</small>
                            <div class="ad-content bg-white border p-4" style="min-height: 250px;">
                                <p class="text-muted mb-0">300 x 250</p>
                            </div>
                        </div>
                    </div>
                    


                    <!-- Related Articles -->
                    @if($relatedArticles->count() > 0)
                    <div class="sidebar-widget mb-4">
                        <h5 class="widget-title mb-4 pb-2" style="border-bottom: 3px solid var(--accent-color); display: inline-block;">
                            {{ $locale == 'id' ? 'Artikel Terkait' : 'Related Articles' }}
                        </h5>
                        
                        @foreach($relatedArticles as $relatedArticle)
                        <div class="related-article mb-4 pb-3 border-bottom">
                            <a href="{{ url($locale . '/article/' . $relatedArticle->slug) }}" 
                               class="text-decoration-none text-dark d-flex gap-3">
                                @if($relatedArticle->featured_image)
                                <div class="related-image flex-shrink-0" style="width: 80px; height: 80px;">
                                    <img src="{{ asset('storage/' . $relatedArticle->featured_image) }}" 
                                         alt="{{ $locale == 'id' ? $relatedArticle->title_id : $relatedArticle->title_en }}" 
                                         class="img-fluid w-100 h-100" style="object-fit: cover;">
                                </div>
                                @else
                                <div class="related-image-placeholder flex-shrink-0 bg-light d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="bi bi-image text-secondary"></i>
                                </div>
                                @endif
                                
                                <div class="flex-grow-1">
                                    <h6 class="related-title mb-2" style="font-size: 0.9rem; line-height: 1.4;">
                                        {{ \Illuminate\Support\Str::limit($locale == 'id' ? $relatedArticle->title_id : $relatedArticle->title_en, 60) }}
                                    </h6>
                                    
                                    <div class="related-meta text-muted small">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        <span>{{ $relatedArticle->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- Ad Space - Bottom -->
                    <div class="sidebar-widget">
                        <div class="ad-space bg-light border text-center p-3">
                            <small class="text-muted d-block mb-2">{{ $locale == 'id' ? 'IKLAN' : 'ADVERTISEMENT' }}</small>
                            <div class="ad-content bg-white border p-4" style="min-height: 250px;">
                                <p class="text-muted mb-0">300 x 250</p>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</div>

<style>
.article-content {
    font-size: 1.1rem;
    line-height: 1.8;
}

.article-content p {
    margin-bottom: 1.5rem;
}

.article-content h2, 
.article-content h3, 
.article-content h4 {
    margin: 2rem 0 1rem 0;
    font-family: 'Playfair Display', serif;
}

.article-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1.5rem 0;
}

.article-content blockquote {
    border-left: 4px solid var(--accent-color);
    padding-left: 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    color: var(--secondary-color);
}

.article-content ul, 
.article-content ol {
    margin-bottom: 1.5rem;
    padding-left: 1.5rem;
}

.widget-title {
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    font-size: 1.25rem;
    border-bottom: 2px solid var(--accent-color);
    padding-bottom: 0.5rem;
}

.related-title {
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    line-height: 1.3;
    font-size: 0.95rem;
}
</style>
@endsection