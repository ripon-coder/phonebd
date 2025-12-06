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
        \Illuminate\Support\Facades\View::composer('layouts.footer', function ($view) {
            $view->with('footerDynamicPages', \App\Models\DynamicPage::select('id', 'title', 'slug', 'sort_order')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->take(5)
                ->get());
            $view->with('footerSupportPages', \App\Models\Page::select('id', 'title', 'slug')
                ->where('is_active', true)
                ->take(5)
                ->get());
        });
    }
}
