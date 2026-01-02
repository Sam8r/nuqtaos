<?php

namespace App\Filament\Resources\FinancialAdjustments\Pages;

use App\Filament\Resources\FinancialAdjustments\FinancialAdjustmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFinancialAdjustment extends CreateRecord
{
    protected static string $resource = FinancialAdjustmentResource::class;
}
