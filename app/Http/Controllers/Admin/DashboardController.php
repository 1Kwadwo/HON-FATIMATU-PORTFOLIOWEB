<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Models\GalleryItem;
use App\Models\Initiative;
use App\Models\NewsArticle;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with statistics.
     */
    public function index()
    {
        // Cache unread contact count with 5 minutes TTL
        $unreadCount = Cache::remember('contact:unread_count', CacheService::CONTACT_COUNT_TTL, function () {
            return ContactSubmission::where('is_read', false)->count();
        });

        $statistics = [
            'total_gallery_items' => GalleryItem::count(),
            'published_articles' => NewsArticle::where('status', 'published')->count(),
            'unread_contacts' => $unreadCount,
            'published_initiatives' => Initiative::where('is_published', true)->count(),
        ];

        $recentContacts = ContactSubmission::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('statistics', 'recentContacts'));
    }
}
