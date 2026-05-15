<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
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
        View::composer('client.partials.navbar', function ($view) {
            $categories = Cache::remember('navbar_categories', 60, function () {
                return Category::select('catename', 'slug')
                    ->where('status', 1)
                    ->orderBy('catename')
                    ->get();
            });

            $brands = Cache::remember('navbar_brands', 60, function () {
                return Brand::select('brandname', 'slug')
                    ->where('status', 1)
                    ->orderBy('brandname')
                    ->get();
            });

            $view->with(compact('categories', 'brands'));
        });
    }
}
