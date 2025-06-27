<?php

namespace App\Providers;

use App\Interfaces\IBlogRepository;
use App\Interfaces\ICategoryRepository;
use App\Repository\BlogRepository;
use App\Repository\CategoryRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IBlogRepository::class, BlogRepository::class);
        $this->app->bind(ICategoryRepository::class, CategoryRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
    }
}
