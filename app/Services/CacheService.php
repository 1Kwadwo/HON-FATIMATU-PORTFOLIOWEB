<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    // Cache TTL constants (in seconds)
    const PAGES_TTL = 3600; // 1 hour
    const NEWS_TTL = 900; // 15 minutes
    const GALLERY_TTL = 1800; // 30 minutes
    const INITIATIVES_TTL = 3600; // 1 hour
    const CONTACT_COUNT_TTL = 300; // 5 minutes

    /**
     * Clear all page-related cache.
     */
    public function clearPageCache(string $slug): void
    {
        Cache::forget("pages:{$slug}");
    }

    /**
     * Clear all news-related cache.
     */
    public function clearNewsCache(): void
    {
        // Clear paginated news cache (up to 10 pages)
        for ($i = 1; $i <= 10; $i++) {
            Cache::forget("news:published:page:{$i}");
        }
    }

    /**
     * Clear all gallery-related cache.
     */
    public function clearGalleryCache(): void
    {
        Cache::forget('gallery:all');
        Cache::forget('gallery:category:events');
        Cache::forget('gallery:category:community');
        Cache::forget('gallery:category:official_duties');
    }

    /**
     * Clear initiatives cache.
     */
    public function clearInitiativesCache(): void
    {
        Cache::forget('initiatives:published');
    }

    /**
     * Clear contact unread count cache.
     */
    public function clearContactCountCache(): void
    {
        Cache::forget('contact:unread_count');
    }

    /**
     * Clear homepage cache (including videos).
     */
    public function clearHomeCache(): void
    {
        Cache::forget('home:videos');
        Cache::forget('home:featured_article');
    }
}
