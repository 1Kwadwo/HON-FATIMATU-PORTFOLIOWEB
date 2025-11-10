<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Traits\LogsAdminActions;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    use LogsAdminActions;

    public function index()
    {
        $socialMedia = [
            'facebook' => SiteSetting::get('social_facebook', ''),
            'twitter' => SiteSetting::get('social_twitter', ''),
            'instagram' => SiteSetting::get('social_instagram', ''),
            'linkedin' => SiteSetting::get('social_linkedin', ''),
        ];

        return view('admin.social-media.index', compact('socialMedia'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'social_facebook' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_linkedin' => 'nullable|url',
        ]);

        SiteSetting::set('social_facebook', $validated['social_facebook'] ?? '');
        SiteSetting::set('social_twitter', $validated['social_twitter'] ?? '');
        SiteSetting::set('social_instagram', $validated['social_instagram'] ?? '');
        SiteSetting::set('social_linkedin', $validated['social_linkedin'] ?? '');

        $this->logUpdate('SocialMedia', 0, ['updated' => 'social_media_links']);

        return redirect()
            ->route('admin.social-media.index')
            ->with('success', 'Social media links updated successfully.');
    }
}
