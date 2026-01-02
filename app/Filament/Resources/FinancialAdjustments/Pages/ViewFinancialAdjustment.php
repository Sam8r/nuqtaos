<?php

namespace App\Filament\Resources\FinancialAdjustments\Pages;

use App\Filament\Resources\FinancialAdjustments\FinancialAdjustmentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFinancialAdjustment extends ViewRecord
{
    protected static string $resource = FinancialAdjustmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
