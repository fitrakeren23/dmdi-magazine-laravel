<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class QueryLocale
{
    /**
     * Set locale using (in order of precedence):
     * 1) route parameter 'locale' (e.g. /en/...)
     * 2) query parameter 'lang' (e.g. ?lang=en)
     * 3) session stored 'app_lang'
     * 4) default 'id'
     */
    public function handle(Request $request, Closure $next)
    {
        // 1) route parameter (preferred for routes like /{locale}/...)
        $routeLocale = null;
        if ($request->route()) {
            // some Laravel versions allow $request->route('locale')
            try {
                $routeLocale = $request->route('locale');
            } catch (\Throwable $e) {
                $routeLocale = null;
            }
        }

        // 2) query param
        $queryLocale = $request->query('lang');

        // 3) session stored locale
        $sessionLocale = session('app_lang');

        $allowed = ['id', 'en'];

        if ($routeLocale && in_array($routeLocale, $allowed, true)) {
            $lang = $routeLocale;
        } elseif ($queryLocale && in_array($queryLocale, $allowed, true)) {
            $lang = $queryLocale;
        } elseif ($sessionLocale && in_array($sessionLocale, $allowed, true)) {
            $lang = $sessionLocale;
        } else {
            $lang = 'id';
        }

        app()->setLocale($lang);
        // persist last choice
        session(['app_lang' => $lang]);

        return $next($request);
    }
}