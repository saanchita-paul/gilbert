<?php

namespace App\Providers;

use App\Services\FullTextSearch\FullTextQuery;
use App\Services\FullTextSearch\FullTextQueryInterface;
use App\Services\FullTextSearch\FullTextSearch;
use App\Services\FullTextSearch\FullTextSearchInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootFullTextSearch();
    }

    /**
     * Binding dependency for Full-Text-Search
     *
     * @return void
     */
    private function bootFullTextSearch()
    {
        $this->app->bind(FullTextQueryInterface::class, fn ($app) => new FullTextQuery());
        $this->app->bind(FullTextSearchInterface::class, FullTextSearch::class);
    }
}
