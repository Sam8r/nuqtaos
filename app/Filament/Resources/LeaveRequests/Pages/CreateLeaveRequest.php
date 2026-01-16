<?php

namespace App\Filament\Resources\LeaveRequests\Pages;

use App\Filament\Resources\LeaveRequests\LeaveRequestResource;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Modules\Leaves\Models\LeaveBalance;

class CreateLeaveRequest extends CreateRecord
{
    protected static string $resource = LeaveRequestResource::class;

    public function getTitle(): string
    {
        return __('leave_requests.pages.create');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['employee_id'] = auth()->user()->employee->id;
        return $data;
    }

    protected function beforeCreate(): void
    {
        $data = $this->data;

        if ($data['type'] === 'paid') {
            $userId = $data['user_id'] ?? auth()->id();
            $requestedDays = count($data['selected_dates'] ?? []);

            $balance = LeaveBalance::where('user_id', $userId)
                ->where('month', Carbon::now()->month)
                ->where('year', Carbon::now()->year)
                ->first();

            $totalAvailable = $balance ? ($balance->current_balance + $balance->accumulated_balance) : 0;

            if ($requestedDays > $totalAvailable) {
                Notification::make()
                    ->title(__('leave_requests.notifications.insufficient_balance.title'))
                    ->body(__('leave_requests.notifications.insufficient_balance.body', ['days' => $totalAvailable]))
                    ->danger()
                    ->send();

                $this->halt();
            }
        }
    }
}
