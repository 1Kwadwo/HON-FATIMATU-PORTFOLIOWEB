<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryItem extends Model
{
    protected $fillable = [
        'title',
        'caption',
        'category',
        'image_path',
        'sort_order',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope a query to only include published gallery items.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope a query to filter gallery items by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to order gallery items by sort_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Get the public URL for the gallery image.
     */
    public function getImageUrlAttribute()
    {
        return Storage::url($this->image_path);
    }

    /**
     * Delete the gallery item along with its associated image file and all generated sizes.
     */
    public function deleteWithImage()
    {
        if ($this->image_path) {
            // Use ImageService to delete all sizes
            $imageService = app(\App\Services\ImageService::class);
            $imageService->delete($this->image_path);
        }

        return $this->delete();
    }
}
