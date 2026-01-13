<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AdminStatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-home';

    protected static ?int $navigationSort = -2;

    public function getWidgets(): array
    {
        return [
            AdminStatsOverview::class,
        ];
    }
}
