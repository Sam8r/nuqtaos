<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Employees\Models\Employee;
use Modules\Invoices\Models\Invoice;
use Modules\Projects\Models\Project;
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
            Stat::make(__('filament.total_employees'), Employee::count())
                ->description(__('filament.number_of_registered_employees'))
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),

            Stat::make(__('filament.total_projects'), Project::count())
                ->description(__('filament.number_of_active_projects'))
                ->descriptionIcon('heroicon-o-folder')
                ->color('success'),

            Stat::make(__('filament.invoices'), Invoice::count())
                ->description(__('filament.this_month', ['count' => Invoice::whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear)
                    ->count()]))
                ->descriptionIcon('heroicon-o-document-text')
                ->color('warning'),

            Stat::make(__('filament.monthly_revenue'),
                number_format(Invoice::whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear)
                    ->sum('total'), 2) . ' ' . $currency)
                ->description(__('filament.total_revenue', ['amount' => number_format(Invoice::sum('total'), 2) . ' ' . $currency]))
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),
        ];
    }
}
