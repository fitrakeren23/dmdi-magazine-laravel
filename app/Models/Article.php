<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';

    protected $fillable = [
        'slug',
        'title_id',
        'title_en',
        'excerpt_id',
        'excerpt_en',
        'content_id',
        'content_en',
        'featured_image',
        'category_id',
        'author',      // lama: string penulis (fallback)
        'author_id',   // new: foreign key ke users.id
        'view_count',
        'is_published',
        'is_featured',
        'qr_code_path'
    ];

    /**
     * Casts
     */
    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'view_count' => 'integer',
    ];

    // Relations
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relation to User (author)
    public function authorUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'author_id');
    }

    // Accessors: convenience to get the displayed title/content/excerpt
public function getTitleAttribute()
{
    if (app()->getLocale() === 'en') {
        return $this->title_en ?: $this->title_id;
    }
    return $this->title_id;
}

public function getContentAttribute()
{
    if (app()->getLocale() === 'en') {
        return $this->content_en ?: $this->content_id;
    }
    return $this->content_id;
}

public function getExcerptAttribute()
{
    if (app()->getLocale() === 'en') {
        return $this->excerpt_en ?: $this->excerpt_id;
    }
    return $this->excerpt_id;
}

    // Helper: get the display name for author (prefers related User, falls back to author string)
    public function getAuthorNameAttribute()
    {
        return $this->authorUser ? $this->authorUser->name : $this->author;
    }

    /**
     * URL accessors for featured image sizes.
     * - featured_image stores medium path (uploads/articles/medium/...)
     * - these helpers derive thumb and original siblings by filename.
     */
    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image ? asset('storage/' . $this->featured_image) : null;
    }

    public function getFeaturedImageThumbUrlAttribute()
    {
        if (! $this->featured_image) {
            return null;
        }
        $basename = basename($this->featured_image);
        $thumbPath = 'uploads/articles/thumb/' . $basename;
        return asset('storage/' . $thumbPath);
    }

    public function getFeaturedImageOriginalUrlAttribute()
    {
        if (! $this->featured_image) {
            return null;
        }
        $basename = basename($this->featured_image);
        $originalPath = 'uploads/articles/original/' . $basename;
        return asset('storage/' . $originalPath);
    }

    /**
     * Scope for published articles
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}