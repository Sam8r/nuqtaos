<?php

namespace App\Filament\Resources\FinancialAdjustments\Pages;

use App\Filament\Resources\FinancialAdjustments\FinancialAdjustmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFinancialAdjustments extends ListRecords
{
    protected static string $resource = FinancialAdjustmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
