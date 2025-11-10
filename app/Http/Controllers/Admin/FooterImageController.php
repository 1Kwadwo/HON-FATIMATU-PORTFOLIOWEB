<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\ImageService;
use App\Traits\LogsAdminActions;
use Illuminate\Http\Request;

class FooterImageController extends Controller
{
    use LogsAdminActions;

    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $footerImage = SiteSetting::get('footer_image', '');

        return view('admin.footer-image.index', compact('footerImage'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'footer_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        try {
            // Delete old image if exists
            $oldImage = SiteSetting::get('footer_image');
            if ($oldImage) {
                $this->imageService->delete($oldImage);
            }

            // Upload new image
            $imagePath = $this->imageService->upload($request->file('footer_image'), 'footer');
            SiteSetting::set('footer_image', $imagePath);

            $this->logUpdate('FooterImage', 0, ['updated' => 'footer_image']);

            return redirect()
                ->route('admin.footer-image.index')
                ->with('success', 'Footer image updated successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update footer image: ' . $e->getMessage());
        }
    }

    public function remove()
    {
        $imagePath = SiteSetting::get('footer_image');
        if ($imagePath) {
            $this->imageService->delete($imagePath);
            SiteSetting::set('footer_image', '');
        }

        return redirect()
            ->route('admin.footer-image.index')
            ->with('success', 'Footer image removed successfully.');
    }
}
