<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\OpenAIService; // Adjust the namespace as neede

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(OpenAIService::class, function ($app) {
            return new OpenAIService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
