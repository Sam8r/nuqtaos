<?php

namespace App\Filament\Resources\FinancialAdjustments;

use App\Filament\Resources\FinancialAdjustments\Pages\CreateFinancialAdjustment;
use App\Filament\Resources\FinancialAdjustments\Pages\EditFinancialAdjustment;
use App\Filament\Resources\FinancialAdjustments\Pages\ListFinancialAdjustments;
use App\Filament\Resources\FinancialAdjustments\Pages\ViewFinancialAdjustment;
use App\Filament\Resources\FinancialAdjustments\Schemas\FinancialAdjustmentForm;
use App\Filament\Resources\FinancialAdjustments\Schemas\FinancialAdjustmentInfolist;
use App\Filament\Resources\FinancialAdjustments\Tables\FinancialAdjustmentsTable;
use Modules\FinancialAdjustments\Models\FinancialAdjustment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FinancialAdjustmentResource extends Resource
{
    protected static ?string $model = FinancialAdjustment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    public static function form(Schema $schema): Schema
    {
        return FinancialAdjustmentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FinancialAdjustmentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FinancialAdjustmentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFinancialAdjustments::route('/'),
            'create' => CreateFinancialAdjustment::route('/create'),
            'view' => ViewFinancialAdjustment::route('/{record}'),
            'edit' => EditFinancialAdjustment::route('/{record}/edit'),
        ];
    }
}
