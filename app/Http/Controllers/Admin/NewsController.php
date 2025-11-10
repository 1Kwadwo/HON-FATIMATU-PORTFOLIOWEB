<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use App\Services\CacheService;
use App\Services\NewsService;
use App\Traits\LogsAdminActions;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    use LogsAdminActions;

    protected NewsService $newsService;
    protected CacheService $cacheService;

    public function __construct(NewsService $newsService, CacheService $cacheService)
    {
        $this->newsService = $newsService;
        $this->cacheService = $cacheService;
    }

    /**
     * Display all articles with status and date.
     */
    public function index(Request $request)
    {
        $query = NewsArticle::with('author')->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $articles = $query->paginate(15);

        return view('admin.news.index', compact('articles'));
    }

    /**
     * Show the form for creating a new article.
     */
    public function create()
    {
        return view('admin.news.create');
    }

    /**
     * Store a newly created article with slug auto-generation from title.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        try {
            $article = $this->newsService->createArticle($validated, auth()->user());

            // Invalidate news cache
            $this->cacheService->clearNewsCache();

            return redirect()
                ->route('admin.news.index')
                ->with('success', 'Article created successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create article: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit(NewsArticle $news)
    {
        return view('admin.news.edit', ['article' => $news]);
    }

    /**
     * Update the specified article with featured image upload handling.
     */
    public function update(Request $request, NewsArticle $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        try {
            $article = $this->newsService->updateArticle($news, $validated);

            // Invalidate news cache
            $this->cacheService->clearNewsCache();

            return redirect()
                ->route('admin.news.index')
                ->with('success', 'Article updated successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update article: ' . $e->getMessage());
        }
    }

    /**
     * Publish an article by changing status and setting published_at.
     */
    public function publish(NewsArticle $news)
    {
        try {
            $this->newsService->publishArticle($news);

            // Invalidate news cache
            $this->cacheService->clearNewsCache();

            return back()->with('success', 'Article published successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to publish article: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified article using NewsService.
     */
    public function destroy(NewsArticle $news)
    {
        try {
            $this->newsService->deleteArticle($news);

            // Invalidate news cache
            $this->cacheService->clearNewsCache();

            return redirect()
                ->route('admin.news.index')
                ->with('success', 'Article deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete article: ' . $e->getMessage());
        }
    }

    /**
     * Remove the featured image from an article.
     */
    public function removeImage(NewsArticle $news)
    {
        try {
            if ($news->featured_image_path) {
                $this->newsService->removeFeaturedImage($news);

                // Invalidate news cache
                $this->cacheService->clearNewsCache();

                return back()->with('success', 'Featured image removed successfully.');
            }

            return back()->with('error', 'No featured image to remove.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to remove image: ' . $e->getMessage());
        }
    }
}
