<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Hugomyb\FilamentMediaAction\Actions\MediaAction;

class EmployeeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label(__('employees.fields.id')),
                TextEntry::make('name')
                    ->label(__('employees.fields.name')),
                TextEntry::make('user.email')
                    ->label(__('employees.fields.email')),
                TextEntry::make('phone')
                    ->label(__('employees.fields.phone')),
                TextEntry::make('emergency_contact')
                    ->label(__('employees.fields.emergency_contact')),
                TextEntry::make('position.name')
                    ->label(__('employees.fields.position'))
                    ->url(fn ($record) => $record->position ? route('filament.admin.resources.positions.view', $record->position) : null),
                TextEntry::make('department.name')
                    ->label(__('employees.fields.department'))
                    ->url(fn ($record) => $record->department ? route('filament.admin.resources.departments.view', $record->department) : null),
                TextEntry::make('contract_type')
                    ->label(__('employees.fields.contract_type'))
                    ->formatStateUsing(fn ($state) => __('employees.contract_types.' . $state)),
                TextEntry::make('work_type')
                    ->label(__('employees.fields.work_type'))
                    ->formatStateUsing(fn ($state) => __('employees.work_types.' . $state)),
                TextEntry::make('status')
                    ->label(__('employees.fields.status'))
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Active' => 'success',
                        'Inactive' => 'warning',
                        'Terminated' => 'danger',
                        'On Leave' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => __('employees.statuses.' . $state)),
                TextEntry::make('salary')
                    ->label(__('employees.fields.salary'))
                    ->state(fn ($record) => number_format($record->salary, 2)),
                TextEntry::make('joining_date')
                    ->label(__('employees.fields.joining_date'))
                    ->date(),
                TextEntry::make('payroll_start_day')
                    ->label(__('employees.fields.payroll_start_day')),
                TextEntry::make('work_start')
                    ->label(__('employees.fields.work_start'))
                    ->state(fn ($record) => date('h:i A', strtotime($record->work_start))),
                TextEntry::make('work_end')
                    ->label(__('employees.fields.work_end'))
                    ->state(fn ($record) => date('h:i A', strtotime($record->work_end))),
                Section::make(__('employees.sections.documents'))
                    ->columnSpanFull()
                    ->schema([
                        RepeatableEntry::make('documents_images')
                            ->label(__('employees.document_labels.images'))
                            ->state(fn ($record) => collect($record->documents ?? [])
                                ->filter(fn ($file) => preg_match('/\.(jpg|jpeg|png|webp)$/i', $file))
                                ->map(fn ($file) => [
                                    'url' => asset('storage/' . $file),
                                    'name' => basename($file),
                                ])
                                ->values()
                                ->toArray()
                            )
                            ->schema([
                                ImageEntry::make('url')
                                    ->label(__('employees.document_labels.images'))
                                    ->square()
                                    ->size(200)
                                    ->extraImgAttributes([
                                        'class' => 'cursor-pointer transition-opacity',
                                    ])
                                    ->action(
                                        MediaAction::make('view')
                                            ->media(fn ($state) => $state)
                                            ->modalHeading(__('employees.modals.view_document'))
                                            ->slideOver()
                                    ),
                                TextEntry::make('name')
                                    ->label(__('employees.document_labels.file_name'))
                                    ->color('gray'),
                            ])
                            ->grid(3)
                            ->columnSpanFull(),

                        TextEntry::make('documents_list')
                            ->label(__('employees.document_labels.other_documents'))
                            ->state(fn ($record) => collect($record->documents ?? [])
                                ->filter(fn ($file) => preg_match('/\.(pdf|doc|docx)$/i', $file))
                                ->map(fn ($file) => __(
                                    'employees.messages.document_link',
                                    [
                                        'url' => asset('storage/' . $file),
                                        'name' => basename($file),
                                    ]
                                ))
                                ->join('')
                            )
                            ->html()
                            ->placeholder(__('employees.messages.no_documents'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
