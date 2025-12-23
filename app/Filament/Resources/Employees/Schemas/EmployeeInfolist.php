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
                TextEntry::make('id')->label('Employee ID'),
                TextEntry::make('name')->label('Name'),
                TextEntry::make('email'),
                TextEntry::make('phone'),
                TextEntry::make('emergency_contact'),
                TextEntry::make('position.name')
                    ->label('Position')
                    ->url(fn ($record) => $record->position ? route('filament.admin.resources.positions.view', $record->position) : null),
                TextEntry::make('department.name')
                    ->label('Department')
                    ->url(fn ($record) => $record->department ? route('filament.admin.resources.departments.view', $record->department) : null),
                TextEntry::make('contract_type'),
                TextEntry::make('status'),
                TextEntry::make('joining_date')->date(),
                Section::make('Documents')
                    ->columnSpanFull()
                    ->schema([
                        RepeatableEntry::make('documents_images')
                            ->label('Images')
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
                                    ->label('Image')
                                    ->square()
                                    ->size(200)
                                    ->extraImgAttributes([
                                        'class' => 'cursor-pointer transition-opacity',
                                    ])
                                    ->action(
                                        MediaAction::make('view')
                                            ->media(fn ($state) => $state)
                                            ->modalHeading('View Document Image')
                                            ->slideOver()
                                    ),
                                TextEntry::make('name')
                                    ->label('File Name')
                                    ->color('gray'),
                            ])
                            ->grid(3)
                            ->columnSpanFull(),

                        TextEntry::make('documents_list')
                            ->label('Other Documents')
                            ->state(fn ($record) => collect($record->documents ?? [])
                                ->filter(fn ($file) => preg_match('/\.(pdf|doc|docx)$/i', $file))
                                ->map(fn ($file) => sprintf(
                                    '<a href="%s" target="_blank" class="text-primary-600 hover:text-primary-800 hover:underline font-medium block mb-1">%s</a>',
                                    asset('storage/' . $file),
                                    basename($file)
                                ))
                                ->join('')
                            )
                            ->html()
                            ->placeholder('No documents available')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
