<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use App\Models\GalleryItem;
use App\Models\Initiative;
use App\Models\NewsArticle;
use App\Models\Page;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Notifications\ContactSubmissionReceived;

class PublicController extends Controller
{
    protected CacheService $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Display the homepage.
     */
    public function home()
    {
        // Query featured news article (most recent published)
        $featuredArticle = NewsArticle::published()
            ->recent()
            ->first();

        // Get recent news articles (limit to 5 for homepage)
        $recentNews = NewsArticle::published()
            ->recent()
            ->take(5)
            ->get();

        // Check if there are more news articles
        $totalNewsCount = NewsArticle::published()->count();
        $hasMoreNews = $totalNewsCount > 5;

        // Retrieve homepage hero image and tagline from pages table
        $homePage = Page::where('slug', 'home')->first();

        // Pass mission statement content to view
        $missionPage = Page::where('slug', 'mission')->first();

        // Get active homepage videos
        $homeVideos = \App\Models\HomeVideo::active()->ordered()->get();

        return view('public.home', [
            'featuredArticle' => $featuredArticle,
            'recentNews' => $recentNews,
            'hasMoreNews' => $hasMoreNews,
            'homePage' => $homePage,
            'missionPage' => $missionPage,
            'homeVideos' => $homeVideos,
            'metaTitle' => 'Hon. Fatimatu Abubakar | Leadership & Legacy',
            'metaDescription' => 'Official website of Hon. Fatimatu Abubakar - Dedicated leader committed to community development, education, and social impact.',
            'ogImage' => $homePage?->featured_image_url ?? asset('images/default-og.jpg'),
        ]);
    }

    /**
     * Display the about page.
     */
    public function about()
    {
        // Retrieve about page content from pages table by slug with 1 hour cache
        $page = Cache::remember('pages:about', CacheService::PAGES_TTL, function () {
            return Page::where('slug', 'about')->firstOrFail();
        });

        // Retrieve published timeline items ordered by sort_order
        $timelineItems = Cache::remember('timeline:published', CacheService::PAGES_TTL, function () {
            return \App\Models\TimelineItem::published()->ordered()->get();
        });

        // Pass page data with meta information to view
        return view('public.about', [
            'page' => $page,
            'timelineItems' => $timelineItems,
            'metaTitle' => $page->meta_title ?? 'About Hon. Fatimatu Abubakar | Biography & Career',
            'metaDescription' => $page->meta_description ?? 'Learn about Hon. Fatimatu Abubakar\'s journey, achievements, and commitment to public service and community development.',
        ]);
    }

    /**
     * Display the initiatives listing page.
     */
    public function initiatives()
    {
        // Query all published initiatives ordered by sort_order with 1 hour cache
        $initiatives = Cache::remember('initiatives:published', CacheService::INITIATIVES_TTL, function () {
            return Initiative::published()
                ->ordered()
                ->get();
        });

        return view('public.initiatives', [
            'initiatives' => $initiatives,
            'metaTitle' => 'Initiatives & Projects | Hon. Fatimatu Abubakar',
            'metaDescription' => 'Explore the community initiatives, programs, and projects led by Hon. Fatimatu Abubakar to create lasting social impact.',
        ]);
    }

    /**
     * Display a single initiative detail page.
     */
    public function initiativeDetail(Initiative $initiative)
    {
        // Ensure the initiative is published
        if (!$initiative->is_published) {
            abort(404);
        }

        // Pass impact statistics to view
        return view('public.initiative-detail', [
            'initiative' => $initiative,
            'metaTitle' => $initiative->title . ' | Hon. Fatimatu Abubakar',
            'metaDescription' => $initiative->short_description,
            'ogImage' => $initiative->featured_image_url,
        ]);
    }

    /**
     * Display the gallery page.
     */
    public function gallery(Request $request)
    {
        $category = $request->get('category', 'all');
        
        // Cache gallery items by category with 30 minutes TTL
        $cacheKey = $category === 'all' ? 'gallery:all' : "gallery:category:{$category}";
        
        $items = Cache::remember($cacheKey, CacheService::GALLERY_TTL, function () use ($category) {
            $query = GalleryItem::published()->ordered();
            
            if ($category !== 'all') {
                $query->byCategory($category);
            }
            
            return $query->get();
        });

        return view('public.gallery', [
            'items' => $items,
            'currentCategory' => $category,
            'metaTitle' => 'Photo Gallery | Hon. Fatimatu Abubakar',
            'metaDescription' => 'Browse photos from events, community engagements, and official duties of Hon. Fatimatu Abubakar.',
        ]);
    }

    /**
     * Display the news listing page.
     */
    public function news(Request $request)
    {
        // Get current page for cache key
        $page = $request->get('page', 1);
        
        // Cache published articles list with 15 minutes TTL
        $articles = Cache::remember("news:published:page:{$page}", CacheService::NEWS_TTL, function () {
            return NewsArticle::published()
                ->recent()
                ->paginate(12);
        });

        return view('public.news.index', [
            'articles' => $articles,
            'metaTitle' => 'News & Updates | Hon. Fatimatu Abubakar',
            'metaDescription' => 'Stay informed with the latest news, announcements, and updates from Hon. Fatimatu Abubakar.',
        ]);
    }

    /**
     * Display a single news article.
     */
    public function newsDetail(NewsArticle $article)
    {
        // Ensure the article is published
        if ($article->status !== 'published') {
            abort(404);
        }

        // Increment view count on article detail page
        $article->incrementViewCount();

        // Get related articles (3 most recent, excluding current)
        $relatedArticles = NewsArticle::published()
            ->recent()
            ->where('id', '!=', $article->id)
            ->limit(3)
            ->get();

        return view('public.news.show', [
            'article' => $article,
            'relatedArticles' => $relatedArticles,
            'metaTitle' => ($article->meta_title ?? $article->title) . ' | Hon. Fatimatu Abubakar',
            'metaDescription' => $article->meta_description ?? $article->excerpt,
            'ogType' => 'article',
            'ogImage' => $article->featured_image_url,
            'articlePublishedTime' => $article->published_at?->toIso8601String(),
            'articleModifiedTime' => $article->updated_at->toIso8601String(),
            'articleAuthor' => $article->author?->name ?? 'Hon. Fatimatu Abubakar',
        ]);
    }

    /**
     * Display the contact page.
     */
    public function contact()
    {
        return view('public.contact', [
            'metaTitle' => 'Contact Us | Hon. Fatimatu Abubakar',
            'metaDescription' => 'Get in touch with Hon. Fatimatu Abubakar\'s office for inquiries, partnership opportunities, or speaking engagements.',
        ]);
    }

    /**
     * Handle contact form submission.
     */
    public function submitContact(Request $request)
    {
        // Validate contact form submission
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Store submission in database with IP address and user agent
        $submission = ContactSubmission::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Invalidate unread count cache
        $this->cacheService->clearContactCountCache();

        // Send email notification to admin
        try {
            $recipientEmail = \App\Models\SiteSetting::get(
                'contact_recipient_email',
                config('mail.admin_email', env('ADMIN_EMAIL', 'admin@example.com'))
            );
            Mail::to($recipientEmail)->send(new \App\Mail\ContactSubmissionReceived($submission));
        } catch (\Exception $e) {
            // Log the error but don't fail the submission
            \Log::error('Failed to send contact notification email: ' . $e->getMessage());
        }

        // Return success message to user
        return redirect()->route('contact')->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}
