<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimelineItem;
use App\Services\CacheService;
use App\Traits\LogsAdminActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TimelineController extends Controller
{
    use LogsAdminActions;

    protected CacheService $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Display all timeline items.
     */
    public function index()
    {
        $items = TimelineItem::orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.timeline.index', compact('items'));
    }

    /**
     * Show the form for creating a new timeline item.
     */
    public function create()
    {
        return view('admin.timeline.create');
    }

    /**
     * Store a newly created timeline item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'period' => 'required|string|max:255',
            'description' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_published' => 'boolean',
        ]);

        try {
            $item = TimelineItem::create([
                'title' => $validated['title'],
                'period' => $validated['period'],
                'description' => $validated['description'],
                'sort_order' => $validated['sort_order'] ?? 0,
                'is_published' => $request->has('is_published'),
            ]);

            // Clear about page cache and timeline cache
            $this->cacheService->clearPageCache('about');
            Cache::forget('timeline:published');

            // Log admin action
            $this->logCreate('TimelineItem', $item->id, [
                'title' => $item->title,
                'period' => $item->period,
            ]);

            return redirect()
                ->route('admin.timeline.index')
                ->with('success', 'Timeline item created successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create timeline item: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified timeline item.
     */
    public function edit(TimelineItem $timeline)
    {
        return view('admin.timeline.edit', ['item' => $timeline]);
    }

    /**
     * Update the specified timeline item.
     */
    public function update(Request $request, TimelineItem $timeline)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'period' => 'required|string|max:255',
            'description' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_published' => 'boolean',
        ]);

        try {
            $timeline->update([
                'title' => $validated['title'],
                'period' => $validated['period'],
                'description' => $validated['description'],
                'sort_order' => $validated['sort_order'] ?? $timeline->sort_order,
                'is_published' => $request->has('is_published'),
            ]);

            // Clear about page cache and timeline cache
            $this->cacheService->clearPageCache('about');
            Cache::forget('timeline:published');

            // Log admin action
            $this->logUpdate('TimelineItem', $timeline->id, [
                'title' => $timeline->title,
                'period' => $timeline->period,
            ]);

            return redirect()
                ->route('admin.timeline.index')
                ->with('success', 'Timeline item updated successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update timeline item: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified timeline item.
     */
    public function destroy(TimelineItem $timeline)
    {
        try {
            // Log admin action before deletion
            $this->logDelete('TimelineItem', $timeline->id, [
                'title' => $timeline->title,
                'period' => $timeline->period,
            ]);

            $timeline->delete();

            // Clear about page cache and timeline cache
            $this->cacheService->clearPageCache('about');
            Cache::forget('timeline:published');

            return redirect()
                ->route('admin.timeline.index')
                ->with('success', 'Timeline item deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete timeline item: ' . $e->getMessage());
        }
    }

    /**
     * Update the sort order of timeline items via AJAX.
     */
    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:timeline_items,id',
        ]);

        try {
            foreach ($validated['order'] as $index => $id) {
                TimelineItem::where('id', $id)->update(['sort_order' => $index]);
            }

            // Clear about page cache and timeline cache
            $this->cacheService->clearPageCache('about');
            Cache::forget('timeline:published');

            // Log admin action
            $this->logUpdate('TimelineItem', null, [
                'action' => 'reorder',
                'count' => count($validated['order']),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Timeline order updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order: ' . $e->getMessage()
            ], 500);
        }
    }
}
