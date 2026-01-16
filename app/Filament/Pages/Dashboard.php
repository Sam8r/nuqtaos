<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AdminStatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = null;
    protected static ?string $navigationLabel = null;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-home';
    protected static ?int $navigationSort = -2;

    public static function boot(): void
    {
        if (app()->getLocale() === 'ar') {
            static::$title = 'لوحة التحكم';
            static::$navigationLabel = 'لوحة التحكم';
        } else {
            static::$title = 'Dashboard';
            static::$navigationLabel = 'Dashboard';
        }
    }

    public function getWidgets(): array
    {
        return [
            AdminStatsOverview::class,
        ];
    }
}
