<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImageService;
use Illuminate\Http\Request;

class TrixUploadController extends Controller
{
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Handle Trix editor image uploads.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        try {
            $path = $this->imageService->upload(
                $request->file('file'),
                'pages/content',
                [
                    'subdirectory' => 'inline',
                    'generateSizes' => false,
                    'quality' => 85
                ]
            );

            return response()->json([
                'url' => '/storage/' . $path
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to upload image: ' . $e->getMessage()
            ], 500);
        }
    }
}
