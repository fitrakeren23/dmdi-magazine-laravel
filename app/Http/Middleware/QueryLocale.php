<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class QueryLocale
{
    /**
     * Menentukan locale berdasarkan query string "lang".
     * Default ke 'id' jika tidak valid.
     */
    public function handle(Request $request, Closure $next)
    {
        $lang = $request->query('lang');

        // Daftar bahasa yang diizinkan
        $allowed = ['id', 'en'];

        if (in_array($lang, $allowed, true)) {
            app()->setLocale($lang);
        } else {
            app()->setLocale('id');
        }

        // (Opsional) Bisa simpan ke session jika ingin persist tanpa query:
        // session(['app_lang' => app()->getLocale()]);

        return $next($request);
    }
}