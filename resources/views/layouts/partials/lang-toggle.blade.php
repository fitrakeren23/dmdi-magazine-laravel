@php
    $currentLocale = app()->getLocale() ?: 'id';
    $other = $currentLocale === 'en' ? 'id' : 'en';

    // Try to build URL using current named route (preferred)
    $routeName = \Illuminate\Support\Facades\Route::currentRouteName();
    $langUrl = null;

    if ($routeName) {
        try {
            $params = request()->route() ? request()->route()->parameters() : [];
            $params['locale'] = $other;
            // If route is named and expects 'locale' param, this should produce correct url
            $langUrl = route($routeName, $params, false);
            if (!\Illuminate\Support\Str::startsWith($langUrl, ['http://','https://'])) {
                $langUrl = url($langUrl);
            }
        } catch (\Throwable $e) {
            $langUrl = null;
        }
    }

    // Fallback: swap the leading /id or /en segment in current URI, else prepend
    if (! $langUrl) {
        $uri = request()->getRequestUri(); // includes query string
        // Replace only the leading /id or /en
        $newUri = preg_replace('#^/(id|en)(/|$)#', '/'.$other.'$2', $uri);
        if ($newUri === null) {
            $newUri = '/' . $other . $uri;
        } elseif ($newUri === $uri && !\Illuminate\Support\Str::startsWith($uri, '/id') && !\Illuminate\Support\Str::startsWith($uri, '/en')) {
            $newUri = '/' . $other . $uri;
        }
        $langUrl = url($newUri);
    }
@endphp

<a href="{{ $langUrl }}" class="lang-toggle text-decoration-none small" rel="alternate" hreflang="{{ $other }}">
    {{ strtoupper($other) }}
</a>