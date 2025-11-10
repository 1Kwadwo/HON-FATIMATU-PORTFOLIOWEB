<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Initiative;
use App\Services\CacheService;
use App\Services\ImageService;
use App\Traits\LogsAdminActions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InitiativeController extends Controller
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
     * Display all initiatives.
     */
    public function index()
    {
        $initiatives = Initiative::orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.initiatives.index', compact('initiatives'));
    }

    /**
     * Show the form for creating a new initiative.
     */
    public function create()
    {
        return view('admin.initiatives.create');
    }

    /**
     * Store a newly created initiative.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:initiatives,slug',
            'short_description' => 'required|string|max:500',
            'full_description' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'impact_stats' => 'nullable|array',
            'impact_stats.*.label' => 'required|string|max:255',
            'impact_stats.*.value' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_published' => 'boolean',
        ]);

        try {
            // Generate slug if not provided
            $slug = $validated['slug'] ?? Str::slug($validated['title']);
            
            // Ensure slug is unique
            $originalSlug = $slug;
            $counter = 1;
            while (Initiative::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Upload featured image if provided
            $imagePath = null;
            if ($request->hasFile('featured_image')) {
                $imagePath = $this->imageService->upload(
                    $request->file('featured_image'),
                    'initiatives',
                    [
                        'subdirectory' => 'featured',
                        'generateSizes' => true,
                        'quality' => 80
                    ]
                );
            }

            // Process impact stats
            $impactStats = null;
            if (!empty($validated['impact_stats'])) {
                $impactStats = array_filter($validated['impact_stats'], function($stat) {
                    return !empty($stat['label']) && !empty($stat['value']);
                });
            }

            // Create initiative
            $initiative = Initiative::create([
                'title' => $validated['title'],
                'slug' => $slug,
                'short_description' => $validated['short_description'],
                'full_description' => $validated['full_description'],
                'featured_image_path' => $imagePath,
                'impact_stats' => $impactStats,
                'sort_order' => $validated['sort_order'] ?? 0,
                'is_published' => $request->has('is_published'),
            ]);

            // Invalidate initiatives cache
            $this->cacheService->clearInitiativesCache();

            // Log admin action
            $this->logCreate('Initiative', $initiative->id, [
                'title' => $initiative->title,
                'slug' => $initiative->slug,
            ]);

            return redirect()
                ->route('admin.initiatives.index')
                ->with('success', 'Initiative created successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create initiative: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified initiative.
     */
    public function edit(Initiative $initiative)
    {
        return view('admin.initiatives.edit', compact('initiative'));
    }

    /**
     * Update the specified initiative.
     */
    public function update(Request $request, Initiative $initiative)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:initiatives,slug,' . $initiative->id,
            'short_description' => 'required|string|max:500',
            'full_description' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'impact_stats' => 'nullable|array',
            'impact_stats.*.label' => 'required|string|max:255',
            'impact_stats.*.value' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_published' => 'boolean',
        ]);

        try {
            // Generate slug if not provided
            $slug = $validated['slug'] ?? Str::slug($validated['title']);
            
            // Ensure slug is unique (excluding current initiative)
            if ($slug !== $initiative->slug) {
                $originalSlug = $slug;
                $counter = 1;
                while (Initiative::where('slug', $slug)->where('id', '!=', $initiative->id)->exists()) {
                    $slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }

            // Upload new featured image if provided
            $imagePath = $initiative->featured_image_path;
            if ($request->hasFile('featured_image')) {
                // Delete old image if exists
                if ($imagePath) {
                    $this->imageService->delete($imagePath);
                }
                
                $imagePath = $this->imageService->upload(
                    $request->file('featured_image'),
                    'initiatives',
                    [
                        'subdirectory' => 'featured',
                        'generateSizes' => true,
                        'quality' => 80
                    ]
                );
            }

            // Process impact stats
            $impactStats = null;
            if (!empty($validated['impact_stats'])) {
                $impactStats = array_filter($validated['impact_stats'], function($stat) {
                    return !empty($stat['label']) && !empty($stat['value']);
                });
            }

            // Update initiative
            $initiative->update([
                'title' => $validated['title'],
                'slug' => $slug,
                'short_description' => $validated['short_description'],
                'full_description' => $validated['full_description'],
                'featured_image_path' => $imagePath,
                'impact_stats' => $impactStats,
                'sort_order' => $validated['sort_order'] ?? $initiative->sort_order,
                'is_published' => $request->has('is_published'),
            ]);

            // Invalidate initiatives cache
            $this->cacheService->clearInitiativesCache();

            // Log admin action
            $this->logUpdate('Initiative', $initiative->id, [
                'title' => $initiative->title,
                'slug' => $initiative->slug,
            ]);

            return redirect()
                ->route('admin.initiatives.index')
                ->with('success', 'Initiative updated successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update initiative: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified initiative.
     */
    public function destroy(Initiative $initiative)
    {
        try {
            // Log admin action before deletion
            $this->logDelete('Initiative', $initiative->id, [
                'title' => $initiative->title,
                'slug' => $initiative->slug,
            ]);

            // Delete featured image if exists
            if ($initiative->featured_image_path) {
                $this->imageService->delete($initiative->featured_image_path);
            }

            // Delete initiative
            $initiative->delete();

            // Invalidate initiatives cache
            $this->cacheService->clearInitiativesCache();

            return redirect()
                ->route('admin.initiatives.index')
                ->with('success', 'Initiative deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete initiative: ' . $e->getMessage());
        }
    }

    /**
     * Update the sort order of initiatives via AJAX.
     */
    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:initiatives,id',
        ]);

        try {
            foreach ($validated['order'] as $index => $id) {
                Initiative::where('id', $id)->update(['sort_order' => $index]);
            }

            // Invalidate initiatives cache
            $this->cacheService->clearInitiativesCache();

            // Log admin action
            $this->logUpdate('Initiative', null, [
                'action' => 'reorder',
                'count' => count($validated['order']),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Initiative order updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the featured image from an initiative.
     */
    public function removeImage(Initiative $initiative)
    {
        try {
            if ($initiative->featured_image_path) {
                // Delete the image file
                $this->imageService->delete($initiative->featured_image_path);

                // Update the initiative
                $initiative->update(['featured_image_path' => null]);

                // Invalidate initiatives cache
                $this->cacheService->clearInitiativesCache();

                // Log admin action
                $this->logUpdate('Initiative', $initiative->id, [
                    'action' => 'remove_image',
                    'title' => $initiative->title,
                ]);

                return back()->with('success', 'Featured image removed successfully.');
            }

            return back()->with('error', 'No featured image to remove.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to remove image: ' . $e->getMessage());
        }
    }
}
