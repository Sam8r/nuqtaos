<?php

namespace App\Filament\Resources\Tasks\Pages;

use App\Filament\Resources\Tasks\TaskResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    protected function afterCreate(): void
    {
        $task = $this->record;
        $employee = $task->employee;

        if ($employee && $employee->user_id) {
            $user = User::find($employee->user_id);

            Notification::make()
                ->title('New Task Assigned')
                ->body("Task: {$task->title} has been assigned to you.")
                ->icon('heroicon-o-clipboard-document-list')
                ->color('success')
                ->sendToDatabase($user);
        }
    }
}
