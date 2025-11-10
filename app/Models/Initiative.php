<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Initiative extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'full_description',
        'featured_image_path',
        'impact_stats',
        'sort_order',
        'is_published',
    ];

    protected $casts = [
        'impact_stats' => 'array',
        'is_published' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope a query to only include published initiatives.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope a query to order initiatives by sort_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
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
}
