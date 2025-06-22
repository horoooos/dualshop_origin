<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
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
         Vite::macro('image', fn ($asset) => $this->asset("resources/images/{$asset}"));

         // Автоматическое копирование картинок при запуске сайта
         if ($this->app->runningInConsole() === false) {
             \Artisan::call('images:copy-to-public');
         }
    }

    private function asset(string $string)
    {
    }
}
