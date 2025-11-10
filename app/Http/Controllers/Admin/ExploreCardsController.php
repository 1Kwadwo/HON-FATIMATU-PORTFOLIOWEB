<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\ImageService;
use App\Traits\LogsAdminActions;
use Illuminate\Http\Request;

class ExploreCardsController extends Controller
{
    use LogsAdminActions;

    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $exploreCards = [
            'about_image' => SiteSetting::get('explore_about_image', ''),
            'initiatives_image' => SiteSetting::get('explore_initiatives_image', ''),
            'news_image' => SiteSetting::get('explore_news_image', ''),
        ];

        return view('admin.explore-cards.index', compact('exploreCards'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'about_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'initiatives_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'news_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        try {
            // Handle About image upload
            if ($request->hasFile('about_image')) {
                $oldImage = SiteSetting::get('explore_about_image');
                if ($oldImage) {
                    $this->imageService->delete($oldImage);
                }
                $imagePath = $this->imageService->upload($request->file('about_image'), 'explore');
                SiteSetting::set('explore_about_image', $imagePath);
            }

            // Handle Initiatives image upload
            if ($request->hasFile('initiatives_image')) {
                $oldImage = SiteSetting::get('explore_initiatives_image');
                if ($oldImage) {
                    $this->imageService->delete($oldImage);
                }
                $imagePath = $this->imageService->upload($request->file('initiatives_image'), 'explore');
                SiteSetting::set('explore_initiatives_image', $imagePath);
            }

            // Handle News image upload
            if ($request->hasFile('news_image')) {
                $oldImage = SiteSetting::get('explore_news_image');
                if ($oldImage) {
                    $this->imageService->delete($oldImage);
                }
                $imagePath = $this->imageService->upload($request->file('news_image'), 'explore');
                SiteSetting::set('explore_news_image', $imagePath);
            }

            $this->logUpdate('ExploreCards', 0, ['updated' => 'explore_cards']);

            return redirect()
                ->route('admin.explore-cards.index')
                ->with('success', 'Explore cards updated successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update explore cards: ' . $e->getMessage());
        }
    }

    public function removeImage(Request $request)
    {
        $card = $request->input('card');
        $settingKey = "explore_{$card}_image";
        
        $imagePath = SiteSetting::get($settingKey);
        if ($imagePath) {
            $this->imageService->delete($imagePath);
            SiteSetting::set($settingKey, '');
        }

        return redirect()
            ->route('admin.explore-cards.index')
            ->with('success', ucfirst($card) . ' image removed successfully.');
    }
}
