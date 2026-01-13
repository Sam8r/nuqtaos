<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Employees\Models\Employee;
use Modules\Invoices\Models\Invoice;
use Modules\Projects\Models\Project;
use Illuminate\Support\Carbon;
use Modules\Settings\Models\Setting;

class AdminStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $currency = Setting::value('currency');

        return [
            Stat::make('Total Employees', Employee::count())
                ->description('Number of registered employees')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Total Projects', Project::count())
                ->description('Number of active projects')
                ->descriptionIcon('heroicon-o-folder')
                ->color('success'),

            Stat::make('Invoices', Invoice::count())
                ->description('This month: ' . Invoice::whereMonth('created_at', $currentMonth)
                        ->whereYear('created_at', $currentYear)
                        ->count())
                ->descriptionIcon('heroicon-o-document-text')
                ->color('warning'),

            Stat::make('Monthly Revenue',
                number_format(Invoice::whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear)
                    ->sum('total'), 2) . ' ' . $currency)
                ->description('Total Revenue: ' . number_format(Invoice::sum('total'), 2) . ' ' . $currency)
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),
        ];
    }
}
