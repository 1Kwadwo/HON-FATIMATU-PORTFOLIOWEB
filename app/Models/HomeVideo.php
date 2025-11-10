<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HomeVideo extends Model
{
    protected $fillable = [
        'title',
        'description',
        'video_url',
        'thumbnail_path',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the embed URL for the video.
     */
    public function getEmbedUrlAttribute()
    {
        $url = $this->video_url;

        // YouTube
        if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $url, $id)) {
            return 'https://www.youtube.com/embed/' . $id[1];
        } elseif (preg_match('/youtube\.com\/embed\/([^\&\?\/]+)/', $url, $id)) {
            return $url;
        } elseif (preg_match('/youtu\.be\/([^\&\?\/]+)/', $url, $id)) {
            return 'https://www.youtube.com/embed/' . $id[1];
        }

        // Vimeo
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $id)) {
            return 'https://player.vimeo.com/video/' . $id[1];
        }

        return $url;
    }

    /**
     * Get the thumbnail URL.
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            return Storage::url($this->thumbnail_path);
        }

        // Try to get YouTube thumbnail
        if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $this->video_url, $id)) {
            return 'https://img.youtube.com/vi/' . $id[1] . '/maxresdefault.jpg';
        } elseif (preg_match('/youtu\.be\/([^\&\?\/]+)/', $this->video_url, $id)) {
            return 'https://img.youtube.com/vi/' . $id[1] . '/maxresdefault.jpg';
        }

        return null;
    }

    /**
     * Scope to get only active videos.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }
}
