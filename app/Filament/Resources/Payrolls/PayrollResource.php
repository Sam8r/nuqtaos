<?php

namespace App\Filament\Resources\Payrolls;

use App\Filament\Resources\Payrolls\Pages\CreatePayroll;
use App\Filament\Resources\Payrolls\Pages\EditPayroll;
use App\Filament\Resources\Payrolls\Pages\ListPayrolls;
use App\Filament\Resources\Payrolls\Pages\ViewPayroll;
use App\Filament\Resources\Payrolls\Schemas\PayrollForm;
use App\Filament\Resources\Payrolls\Schemas\PayrollInfolist;
use App\Filament\Resources\Payrolls\Tables\PayrollsTable;
use Modules\FinancialAdjustments\Models\FinancialAdjustment;
use Modules\Payrolls\Models\Payroll;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PayrollResource extends Resource
{
    protected static ?string $model = Payroll::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    public static function getNavigationLabel(): string
    {
        return __('payrolls.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('payrolls.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('payrolls.plural_model_label');
    }

    public static function form(Schema $schema): Schema
    {
        return PayrollForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PayrollInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PayrollsTable::configure($table);
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
            'index' => ListPayrolls::route('/'),
            'create' => CreatePayroll::route('/create'),
            'view' => ViewPayroll::route('/{record}'),
            'edit' => EditPayroll::route('/{record}/edit'),
        ];
    }

    protected static function afterCreate($record, array $data): void
    {
        self::processFinancialAdjustments($data);
    }
    protected static function afterSave($record, array $data): void
    {
        self::processFinancialAdjustments($data);
    }

    protected static function processFinancialAdjustments(array $data): void
    {
        $actions = $data['adjustments_actions'] ?? [];

        if (empty($actions)) {
            return;
        }

        foreach ($actions as $adjustmentId => $decision) {
            $adjustment = FinancialAdjustment::find($adjustmentId);

            if ($adjustment) {
                $adjustment->update([
                    'status' => ($decision === 'approve') ? 'Approved' : 'Rejected',
                ]);
            }
        }
    }
}
