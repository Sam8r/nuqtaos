<?php

namespace App\Providers;

use App\Filament\Custom\TextEntry;
use Illuminate\Support\ServiceProvider;
use Filament\Infolists\Components\TextEntry as BaseTextEntry;

class CustomFilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(BaseTextEntry::class, TextEntry::class);
    }
}
