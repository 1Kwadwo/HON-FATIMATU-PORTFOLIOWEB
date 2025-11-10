<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageRevision;
use App\Services\CacheService;
use App\Services\PageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    protected PageService $pageService;
    protected CacheService $cacheService;

    public function __construct(PageService $pageService, CacheService $cacheService)
    {
        $this->pageService = $pageService;
        $this->cacheService = $cacheService;
    }

    /**
     * Display a listing of editable pages.
     */
    public function index()
    {
        $pages = Page::whereIn('slug', ['home', 'about', 'initiatives'])
            ->with('updatedBy:id,name')
            ->orderBy('title')
            ->get();

        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for editing the specified page.
     */
    public function edit(Page $page)
    {
        // Only allow editing of specific pages
        if (!in_array($page->slug, ['home', 'about', 'initiatives'])) {
            return redirect()->route('admin.pages.index')
                ->with('error', 'This page cannot be edited.');
        }

        $page->load('updatedBy:id,name');

        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified page in storage.
     */
    public function update(Request $request, Page $page)
    {
        // Only allow editing of specific pages
        if (!in_array($page->slug, ['home', 'about', 'initiatives'])) {
            return redirect()->route('admin.pages.index')
                ->with('error', 'This page cannot be edited.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'hero_image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        // Handle hero image upload
        if ($request->hasFile('hero_image')) {
            $heroImage = $request->file('hero_image');
            $path = $heroImage->store('pages/hero-images', 'public');
            $validated['hero_image'] = '/storage/' . $path;
            
            // Delete old hero image if exists
            if ($page->hero_image && file_exists(public_path($page->hero_image))) {
                @unlink(public_path($page->hero_image));
            }
        }

        try {
            $this->pageService->updatePage($page, $validated, Auth::user());

            // Invalidate page cache
            $this->cacheService->clearPageCache($page->slug);

            return redirect()->route('admin.pages.edit', $page)
                ->with('success', 'Page updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update page: ' . $e->getMessage());
        }
    }

    /**
     * Display the revision history for the specified page.
     */
    public function revisions(Page $page)
    {
        // Only allow viewing revisions for specific pages
        if (!in_array($page->slug, ['home', 'about', 'initiatives'])) {
            return redirect()->route('admin.pages.index')
                ->with('error', 'This page cannot be accessed.');
        }

        $revisions = $this->pageService->getRevisionHistory($page, 10);

        return view('admin.pages.revisions', compact('page', 'revisions'));
    }

    /**
     * Restore a specific revision.
     */
    public function restore(PageRevision $revision)
    {
        $page = $revision->page;

        // Only allow restoring revisions for specific pages
        if (!in_array($page->slug, ['home', 'about', 'initiatives'])) {
            return redirect()->route('admin.pages.index')
                ->with('error', 'This page cannot be restored.');
        }

        try {
            $this->pageService->restoreRevision($revision, Auth::user());

            // Invalidate page cache
            $this->cacheService->clearPageCache($page->slug);

            return redirect()->route('admin.pages.edit', $page)
                ->with('success', 'Page restored to previous revision successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to restore revision: ' . $e->getMessage());
        }
    }

    /**
     * Remove the hero image from a page.
     */
    public function removeHeroImage(Page $page)
    {
        // Only allow removing hero images for specific pages
        if (!in_array($page->slug, ['home', 'about'])) {
            return redirect()->route('admin.pages.index')
                ->with('error', 'This page does not have a hero image.');
        }

        try {
            if ($page->hero_image) {
                // Delete the old hero image file
                if (file_exists(public_path($page->hero_image))) {
                    @unlink(public_path($page->hero_image));
                }

                // Update the page
                $page->update(['hero_image' => null]);

                // Invalidate page cache
                $this->cacheService->clearPageCache($page->slug);

                return back()->with('success', 'Hero image removed successfully.');
            }

            return back()->with('error', 'No hero image to remove.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to remove hero image: ' . $e->getMessage());
        }
    }
}
