<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeVideo;
use App\Services\CacheService;
use App\Services\ImageService;
use App\Traits\LogsAdminActions;
use Illuminate\Http\Request;

class HomeVideoController extends Controller
{
    use LogsAdminActions;

    protected ImageService $imageService;
    protected CacheService $cacheService;

    public function __construct(ImageService $imageService, CacheService $cacheService)
    {
        $this->imageService = $imageService;
        $this->cacheService = $cacheService;
    }

    public function index()
    {
        $videos = HomeVideo::ordered()->get();
        return view('admin.home-videos.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.home-videos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'video_url' => 'required|url',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        try {
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $this->imageService->upload(
                    $request->file('thumbnail'),
                    'videos/thumbnails'
                );
            }

            $video = HomeVideo::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'video_url' => $validated['video_url'],
                'thumbnail_path' => $thumbnailPath,
                'sort_order' => $validated['sort_order'] ?? 0,
                'is_active' => $request->has('is_active'),
            ]);

            $this->cacheService->clearHomeCache();
            $this->logCreate('HomeVideo', $video->id, ['title' => $video->title]);

            return redirect()
                ->route('admin.home-videos.index')
                ->with('success', 'Video added successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to add video: ' . $e->getMessage());
        }
    }

    public function edit(HomeVideo $homeVideo)
    {
        return view('admin.home-videos.edit', ['video' => $homeVideo]);
    }

    public function update(Request $request, HomeVideo $homeVideo)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'video_url' => 'required|url',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        try {
            $thumbnailPath = $homeVideo->thumbnail_path;
            
            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail if exists
                if ($thumbnailPath) {
                    $this->imageService->delete($thumbnailPath);
                }
                
                $thumbnailPath = $this->imageService->upload(
                    $request->file('thumbnail'),
                    'videos/thumbnails'
                );
            }

            $homeVideo->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'video_url' => $validated['video_url'],
                'thumbnail_path' => $thumbnailPath,
                'sort_order' => $validated['sort_order'] ?? $homeVideo->sort_order,
                'is_active' => $request->has('is_active'),
            ]);

            $this->cacheService->clearHomeCache();
            $this->logUpdate('HomeVideo', $homeVideo->id, ['title' => $homeVideo->title]);

            return redirect()
                ->route('admin.home-videos.index')
                ->with('success', 'Video updated successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update video: ' . $e->getMessage());
        }
    }

    public function destroy(HomeVideo $homeVideo)
    {
        try {
            $this->logDelete('HomeVideo', $homeVideo->id, ['title' => $homeVideo->title]);

            if ($homeVideo->thumbnail_path) {
                $this->imageService->delete($homeVideo->thumbnail_path);
            }

            $homeVideo->delete();
            $this->cacheService->clearHomeCache();

            return redirect()
                ->route('admin.home-videos.index')
                ->with('success', 'Video deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete video: ' . $e->getMessage());
        }
    }
}
