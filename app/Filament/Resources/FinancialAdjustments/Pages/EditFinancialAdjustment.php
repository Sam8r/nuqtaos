<?php

namespace App\Filament\Resources\FinancialAdjustments\Pages;

use App\Filament\Resources\FinancialAdjustments\FinancialAdjustmentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditFinancialAdjustment extends EditRecord
{
    protected static string $resource = FinancialAdjustmentResource::class;

    public function getTitle(): string
    {
        return __('financial_adjustments.page.edit');
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->label(__('financial_adjustments.actions.view')),
            DeleteAction::make()
                ->label(__('financial_adjustments.actions.delete')),
        ];
    }
}
