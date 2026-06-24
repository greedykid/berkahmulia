<?php

namespace App\Providers;

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
        if (str_contains(request()->getHost(), 'trycloudflare.com') || request()->header('X-Forwarded-Proto') === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Dynamically override contact settings from database
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $whatsapp = \App\Models\Setting::get('whatsapp_number');
                if ($whatsapp) {
                    config(['app.whatsapp_number' => $whatsapp]);
                }
                $email = \App\Models\Setting::get('admin_email');
                if ($email) {
                    config(['app.admin_email' => $email]);
                }
            }
        } catch (\Exception $e) {
            // Silence exceptions during migration / setup
        }

        // Share nav categories with all public layout views
        \Illuminate\Support\Facades\View::composer('layouts.app', function ($view) {
            $navCategories = collect(\Illuminate\Support\Facades\Cache::remember('nav_categories', 3600, function () {
                return \App\Models\Category::all()->map(function ($cat) {
                    return [
                        'id' => $cat->id,
                        'name' => $cat->name,
                        'slug' => $cat->slug,
                        'image_path' => $cat->image_path,
                    ];
                })->toArray();
            }))->map(fn($cat) => (object) $cat);

            $view->with('navCategories', $navCategories);
        });
    }
}
