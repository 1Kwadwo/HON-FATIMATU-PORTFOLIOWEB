<?php

namespace App\Providers;

use App\Listeners\LogAuthenticationEvents;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register authentication event subscriber
        Event::subscribe(LogAuthenticationEvents::class);

        // Share social media settings with all views
        View::composer('*', function ($view) {
            $view->with('socialMedia', [
                'facebook' => SiteSetting::get('social_facebook', ''),
                'twitter' => SiteSetting::get('social_twitter', ''),
                'instagram' => SiteSetting::get('social_instagram', ''),
                'linkedin' => SiteSetting::get('social_linkedin', ''),
            ]);
        });
    }
}
