<?php

namespace App\Filament\Resources\LeaveRequests\Pages;

use App\Filament\Resources\LeaveRequests\LeaveRequestResource;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Modules\Leaves\Models\LeaveBalance;

class ViewLeaveRequest extends ViewRecord
{
    protected static string $resource = LeaveRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Approve')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->requiresConfirmation()
                ->action(function ($record) {
                    // 1. Check if the leave is PAID to deduct from balance
                    if ($record->type === 'paid' && !$record->is_encashed) {
                        $requestedDays = count($record->selected_dates ?? []);

                        // Get current month balance
                        $balance = LeaveBalance::where('employee_id', $record->employee_id)
                            ->where('month', Carbon::now()->month)
                            ->where('year', Carbon::now()->year)
                            ->first();

                        // Deduction Logic: Deduct from current_balance first, then accumulated
                        if ($balance->current_balance >= $requestedDays) {
                            $balance->decrement('current_balance', $requestedDays);
                        } else {
                            $remaining = $requestedDays - $balance->current_balance;
                            $balance->update([
                                'current_balance' => 0,
                                'accumulated_balance' => $balance->accumulated_balance - $remaining
                            ]);
                        }
                    }

                    $record->update(['status' => 'Approved']);

                    Notification::make()->title('Request Approved')->success()->send();
                })
                ->visible(fn ($record) => $record->status === 'Pending'),
            Action::make('reject')
                ->label('Reject')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->requiresConfirmation()
                ->form([
                    Textarea::make('rejection_reason')
                        ->label('Reason for rejection')
                        ->required(),
                ])
                ->action(function ($record, array $data) {
                    $record->update([
                        'status' => 'Rejected',
                        'rejection_reason' => $data['rejection_reason']
                    ]);

                    Notification::make()->title('Request Rejected')->danger()->send();
                })
                ->visible(fn ($record) => $record->status === 'Pending'),
            DeleteAction::make(),
        ];
    }
}
