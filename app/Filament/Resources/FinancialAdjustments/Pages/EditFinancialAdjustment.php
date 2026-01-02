<?php

namespace App\Filament\Resources\FinancialAdjustments\Pages;

use App\Filament\Resources\FinancialAdjustments\FinancialAdjustmentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditFinancialAdjustment extends EditRecord
{
    protected static string $resource = FinancialAdjustmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
