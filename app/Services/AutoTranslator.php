<?php

namespace App\Services;

use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Str;

class AutoTranslator
{
    protected string $source;
    protected string $target;
    protected bool $enabled;

    public function __construct()
    {
        $this->enabled = filter_var(env('AUTO_TRANSLATE_ENABLED', true), FILTER_VALIDATE_BOOL);
        $this->source  = env('AUTO_TRANSLATE_SOURCE', 'id');
        $this->target  = env('AUTO_TRANSLATE_TARGET', 'en');
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function translate(?string $text): string
    {
        if (!$this->enabled || !is_string($text) || trim($text) === '') {
            return (string) $text;
        }

        $original = trim($text);

        if (Str::length($original) > 8000) {
            $original = Str::limit($original, 8000, ' [trimmed]');
        }

        try {
            $tr = new GoogleTranslate();
            $tr->setSource($this->source);
            $tr->setTarget($this->target);
            return $tr->translate($original);
        } catch (\Throwable $e) {
            logger()->warning('AutoTranslate failed: '.$e->getMessage());
            return (string) $text;
        }
    }

    // Versi sederhana untuk konten HTML: translate per paragraf
    public function translateHtml(string $html): string
    {
        if (!$this->enabled || trim($html) === '') {
            return $html;
        }

        // Jika sudah ada <p>, ambil tiap paragraf
        if (stripos($html, '<p') !== false) {
            $out = preg_replace_callback('/<p[^>]*>(.*?)<\/p>/is', function ($m) {
                $plain = trim(strip_tags($m[1]));
                if ($plain === '') {
                    return $m[0];
                }
                $translated = $this->translate($plain);
                return '<p>'.e($translated).'</p>';
            }, $html);

            if ($out !== null) {
                return $out;
            }
        }

        // Fallback: translate seluruh plain text lalu bungkus sederhana
        $plain = strip_tags($html);
        $translated = $this->translate($plain);
        return nl2br(e($translated));
    }
}