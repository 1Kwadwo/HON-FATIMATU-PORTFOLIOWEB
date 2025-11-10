<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Traits\LogsAdminActions;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    use LogsAdminActions;

    protected $imageService;

    public function __construct(\App\Services\ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $settings = [
            'foundation_name' => SiteSetting::get('foundation_name', ''),
            'foundation_url' => SiteSetting::get('foundation_url', ''),
            'foundation_description' => SiteSetting::get('foundation_description', ''),
            'foundation_image' => SiteSetting::get('foundation_image', ''),
            'foundation_enabled' => SiteSetting::get('foundation_enabled', '0') === '1',
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'foundation_name' => 'required|string|max:255',
            'foundation_url' => 'required|url',
            'foundation_description' => 'nullable|string|max:500',
            'foundation_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'foundation_enabled' => 'boolean',
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('foundation_image')) {
                // Delete old image if exists
                $oldImage = SiteSetting::get('foundation_image');
                if ($oldImage) {
                    $this->imageService->delete($oldImage);
                }

                // Upload new image
                $imagePath = $this->imageService->upload(
                    $request->file('foundation_image'),
                    'foundation'
                );
                SiteSetting::set('foundation_image', $imagePath);
            }

            SiteSetting::set('foundation_name', $validated['foundation_name']);
            SiteSetting::set('foundation_url', $validated['foundation_url']);
            SiteSetting::set('foundation_description', $validated['foundation_description'] ?? '');
            SiteSetting::set('foundation_enabled', $request->has('foundation_enabled') ? '1' : '0');

            $this->logUpdate('SiteSettings', 0, ['foundation' => $validated['foundation_name']]);

            return redirect()
                ->route('admin.settings.index')
                ->with('success', 'Settings updated successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }

    public function removeImage()
    {
        $imagePath = SiteSetting::get('foundation_image');
        if ($imagePath) {
            $this->imageService->delete($imagePath);
            SiteSetting::set('foundation_image', '');
        }

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Foundation image removed successfully.');
    }
}
