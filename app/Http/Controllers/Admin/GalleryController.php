<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use App\Services\CacheService;
use App\Services\ImageService;
use App\Traits\LogsAdminActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    use LogsAdminActions;

    protected ImageService $imageService;
    protected CacheService $cacheService;

    public function __construct(ImageService $imageService, CacheService $cacheService)
    {
        $this->imageService = $imageService;
        $this->cacheService = $cacheService;
    }

    /**
     * Display all gallery items with thumbnails.
     */
    public function index(Request $request)
    {
        $query = GalleryItem::query()->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');

        // Filter by category if provided
        if ($request->has('category') && $request->category !== 'all') {
            $query->byCategory($request->category);
        }

        // Filter by published status if provided
        if ($request->has('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'unpublished') {
                $query->where('is_published', false);
            }
        }

        $items = $query->paginate(12);

        return view('admin.gallery.index', compact('items'));
    }

    /**
     * Show the form for creating a new gallery item.
     */
    public function create()
    {
        return view('admin.gallery.create');
    }

    /**
     * Store a newly created gallery item with image upload validation and ImageService integration.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'caption' => 'nullable|string|max:1000',
            'category' => 'required|in:events,community,official_duties',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'sort_order' => 'nullable|integer|min:0',
            'is_published' => 'boolean',
        ]);

        try {
            // Upload image using ImageService with multiple size generation
            $imagePath = $this->imageService->upload(
                $request->file('image'),
                'gallery',
                [
                    'subdirectory' => 'original',
                    'generateSizes' => true,
                    'quality' => 80
                ]
            );

            // Create gallery item
            $galleryItem = GalleryItem::create([
                'title' => $validated['title'],
                'caption' => $validated['caption'] ?? null,
                'category' => $validated['category'],
                'image_path' => $imagePath,
                'sort_order' => $validated['sort_order'] ?? 0,
                'is_published' => $request->has('is_published'),
            ]);

            // Invalidate gallery cache
            $this->cacheService->clearGalleryCache();

            // Log admin action
            $this->logCreate('GalleryItem', $galleryItem->id, [
                'title' => $galleryItem->title,
                'category' => $galleryItem->category,
            ]);

            return redirect()
                ->route('admin.gallery.index')
                ->with('success', 'Gallery item created successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to upload image: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified gallery item.
     */
    public function edit(GalleryItem $gallery)
    {
        return view('admin.gallery.edit', ['galleryItem' => $gallery]);
    }

    /**
     * Update the specified gallery item for caption and category editing.
     */
    public function update(Request $request, GalleryItem $gallery)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'caption' => 'nullable|string|max:1000',
            'category' => 'required|in:events,community,official_duties',
            'sort_order' => 'nullable|integer|min:0',
            'is_published' => 'boolean',
        ]);

        $gallery->update([
            'title' => $validated['title'],
            'caption' => $validated['caption'] ?? null,
            'category' => $validated['category'],
            'sort_order' => $validated['sort_order'] ?? $gallery->sort_order,
            'is_published' => $request->has('is_published'),
        ]);

        // Invalidate gallery cache
        $this->cacheService->clearGalleryCache();

        // Log admin action
        $this->logUpdate('GalleryItem', $gallery->id, [
            'title' => $gallery->title,
            'category' => $gallery->category,
        ]);

        return redirect()
            ->route('admin.gallery.index')
            ->with('success', 'Gallery item updated successfully.');
    }

    /**
     * Remove the specified gallery item using deleteWithImage().
     */
    public function destroy(GalleryItem $gallery)
    {
        try {
            // Log admin action before deletion
            $this->logDelete('GalleryItem', $gallery->id, [
                'title' => $gallery->title,
                'category' => $gallery->category,
            ]);

            // Delete gallery item and all image sizes
            $gallery->deleteWithImage();

            // Invalidate gallery cache
            $this->cacheService->clearGalleryCache();

            return redirect()
                ->route('admin.gallery.index')
                ->with('success', 'Gallery item deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete gallery item: ' . $e->getMessage());
        }
    }
}
