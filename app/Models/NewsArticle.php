<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class NewsArticle extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image_path',
        'author_id',
        'status',
        'published_at',
        'meta_title',
        'meta_description',
        'view_count',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Get the author of the news article.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope a query to only include published articles.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope a query to order articles by most recent.
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    /**
     * Get the public URL for the featured image.
     */
    public function getFeaturedImageUrlAttribute()
    {
        if (!$this->featured_image_path) {
            return null;
        }

        return Storage::url($this->featured_image_path);
    }

    /**
     * Get the estimated reading time in minutes.
     */
    public function getReadingTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / 200); // Average reading speed: 200 words per minute

        return $minutes;
    }

    /**
     * Publish the article.
     */
    public function publish()
    {
        $this->status = 'published';
        $this->published_at = $this->published_at ?? now();
        $this->save();

        return $this;
    }

    /**
     * Unpublish the article.
     */
    public function unpublish()
    {
        $this->status = 'draft';
        $this->save();

        return $this;
    }

    /**
     * Increment the view count.
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }
}
