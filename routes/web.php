<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', [App\Http\Controllers\PublicController::class, 'home'])->name('home');
Route::get('/about', [App\Http\Controllers\PublicController::class, 'about'])->name('about');
Route::get('/initiatives', [App\Http\Controllers\PublicController::class, 'initiatives'])->name('initiatives');
Route::get('/initiatives/{initiative:slug}', [App\Http\Controllers\PublicController::class, 'initiativeDetail'])->name('initiatives.show');
Route::get('/gallery', [App\Http\Controllers\PublicController::class, 'gallery'])->name('gallery');
Route::get('/news', [App\Http\Controllers\PublicController::class, 'news'])->name('news');
Route::get('/news/{article:slug}', [App\Http\Controllers\PublicController::class, 'newsDetail'])->name('news.show');
Route::get('/contact', [App\Http\Controllers\PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [App\Http\Controllers\PublicController::class, 'submitContact'])
    ->middleware('throttle:5,60') // 5 submissions per hour per IP
    ->name('contact.submit');

// Sitemap
Route::get('/sitemap.xml', function() {
    $pages = [
        ['url' => '/', 'lastmod' => now()->toIso8601String(), 'changefreq' => 'weekly', 'priority' => '1.0'],
        ['url' => '/about', 'lastmod' => now()->toIso8601String(), 'changefreq' => 'monthly', 'priority' => '0.9'],
        ['url' => '/initiatives', 'lastmod' => now()->toIso8601String(), 'changefreq' => 'weekly', 'priority' => '0.9'],
        ['url' => '/gallery', 'lastmod' => now()->toIso8601String(), 'changefreq' => 'weekly', 'priority' => '0.8'],
        ['url' => '/news', 'lastmod' => now()->toIso8601String(), 'changefreq' => 'daily', 'priority' => '0.9'],
        ['url' => '/contact', 'lastmod' => now()->toIso8601String(), 'changefreq' => 'yearly', 'priority' => '0.7'],
    ];
    
    $articles = \App\Models\NewsArticle::published()->get();
    $initiatives = \App\Models\Initiative::published()->get();
    
    return response()->view('sitemap', compact('pages', 'articles', 'initiatives'))
        ->header('Content-Type', 'text/xml');
})->name('sitemap');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Admin routes
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->name('dashboard');
    
    // Gallery management
    Route::resource('gallery', App\Http\Controllers\Admin\GalleryController::class);
    
    // Homepage Videos management
    Route::resource('home-videos', App\Http\Controllers\Admin\HomeVideoController::class);
    
    // News management
    Route::resource('news', App\Http\Controllers\Admin\NewsController::class);
    Route::post('news/{news}/publish', [App\Http\Controllers\Admin\NewsController::class, 'publish'])
        ->name('news.publish');
    Route::delete('news/{news}/remove-image', [App\Http\Controllers\Admin\NewsController::class, 'removeImage'])
        ->name('news.remove-image');
    
    // Page management
    Route::get('pages', [App\Http\Controllers\Admin\PageController::class, 'index'])
        ->name('pages.index');
    Route::get('pages/{page}/edit', [App\Http\Controllers\Admin\PageController::class, 'edit'])
        ->name('pages.edit');
    Route::put('pages/{page}', [App\Http\Controllers\Admin\PageController::class, 'update'])
        ->name('pages.update');
    Route::get('pages/{page}/revisions', [App\Http\Controllers\Admin\PageController::class, 'revisions'])
        ->name('pages.revisions');
    Route::post('pages/revisions/{revision}/restore', [App\Http\Controllers\Admin\PageController::class, 'restore'])
        ->name('pages.restore');
    Route::delete('pages/{page}/remove-hero-image', [App\Http\Controllers\Admin\PageController::class, 'removeHeroImage'])
        ->name('pages.remove-hero-image');
    
    // Contact submissions management
    Route::get('contacts', [App\Http\Controllers\Admin\ContactSubmissionController::class, 'index'])
        ->name('contacts.index');
    Route::get('contacts/{contact}', [App\Http\Controllers\Admin\ContactSubmissionController::class, 'show'])
        ->name('contacts.show');
    Route::post('contacts/{contact}/mark-as-read', [App\Http\Controllers\Admin\ContactSubmissionController::class, 'markAsRead'])
        ->name('contacts.mark-as-read');
    Route::delete('contacts/{contact}', [App\Http\Controllers\Admin\ContactSubmissionController::class, 'destroy'])
        ->name('contacts.destroy');
    
    // Initiatives management
    Route::resource('initiatives', App\Http\Controllers\Admin\InitiativeController::class);
    Route::post('initiatives/update-order', [App\Http\Controllers\Admin\InitiativeController::class, 'updateOrder'])
        ->name('initiatives.update-order');
    
    // Site Settings
    Route::get('settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])
        ->name('settings.index');
    Route::put('settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])
        ->name('settings.update');
    Route::delete('settings/remove-image', [App\Http\Controllers\Admin\SettingsController::class, 'removeImage'])
        ->name('settings.remove-image');
    
    // Social Media Links
    Route::get('social-media', [App\Http\Controllers\Admin\SocialMediaController::class, 'index'])
        ->name('social-media.index');
    Route::put('social-media', [App\Http\Controllers\Admin\SocialMediaController::class, 'update'])
        ->name('social-media.update');
    
    // Contact Information
    Route::get('contact-info', [App\Http\Controllers\Admin\ContactInfoController::class, 'index'])
        ->name('contact-info.index');
    Route::put('contact-info', [App\Http\Controllers\Admin\ContactInfoController::class, 'update'])
        ->name('contact-info.update');
    
    // Explore Cards
    Route::get('explore-cards', [App\Http\Controllers\Admin\ExploreCardsController::class, 'index'])
        ->name('explore-cards.index');
    Route::put('explore-cards', [App\Http\Controllers\Admin\ExploreCardsController::class, 'update'])
        ->name('explore-cards.update');
    Route::post('explore-cards/remove-image', [App\Http\Controllers\Admin\ExploreCardsController::class, 'removeImage'])
        ->name('explore-cards.remove-image');
    
    // Footer Image
    Route::get('footer-image', [App\Http\Controllers\Admin\FooterImageController::class, 'index'])
        ->name('footer-image.index');
    Route::put('footer-image', [App\Http\Controllers\Admin\FooterImageController::class, 'update'])
        ->name('footer-image.update');
    Route::post('footer-image/remove', [App\Http\Controllers\Admin\FooterImageController::class, 'remove'])
        ->name('footer-image.remove');
    
    Route::delete('initiatives/{initiative}/remove-image', [App\Http\Controllers\Admin\InitiativeController::class, 'removeImage'])
        ->name('initiatives.remove-image');
    
    // Career Timeline management
    Route::resource('timeline', App\Http\Controllers\Admin\TimelineController::class);
    Route::post('timeline/update-order', [App\Http\Controllers\Admin\TimelineController::class, 'updateOrder'])
        ->name('timeline.update-order');
    
    // Trix editor image uploads
    Route::post('trix/upload', [App\Http\Controllers\Admin\TrixUploadController::class, 'upload'])
        ->name('trix.upload');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
