<?php

namespace App\Filament\Resources\Expenses\Pages;

use App\Filament\Resources\Expenses\ExpenseResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewExpense extends ViewRecord
{
    protected static string $resource = ExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label(__('expenses.actions.approve'))
                ->visible(fn ($record) => $record->status === 'Pending')
                ->color('success')
                ->icon('heroicon-o-check')
                ->action(function ($record) {
                    $record->update([
                        'status' => 'Approved',
                        'approved_by' => auth()->id(),
                    ]);
                }),
            Action::make('reject')
                ->label(__('expenses.actions.reject'))
                ->visible(fn ($record) => $record->status === 'Pending')
                ->color('danger')
                ->icon('heroicon-o-x-mark')
                ->requiresConfirmation()
                ->action(fn ($record) => $record->update([
                    'status' => 'Rejected',
                ])),
            EditAction::make(),
        ];
    }
}
