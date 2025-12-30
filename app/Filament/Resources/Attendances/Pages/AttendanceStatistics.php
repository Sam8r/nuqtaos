<?php

namespace App\Filament\Resources\Attendances\Pages;

use App\Filament\Resources\Attendances\AttendanceResource;
use App\Filament\Resources\Attendances\Widgets\AttendanceStatsView;
use Filament\Resources\Pages\Page;

class AttendanceStatistics extends Page
{
    protected static string $resource = AttendanceResource::class;

    protected string $view = 'filament.resources.attendances.pages.attendance-statistics';

    protected function getHeaderWidgets(): array
    {
        return [
            AttendanceStatsView::class,
        ];
    }
}
