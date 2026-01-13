<?php

namespace App\Filament\Resources\Expenses\Schemas;

use App\Filament\Custom\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry as TextEntryComponent;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Hugomyb\FilamentMediaAction\Actions\MediaAction;
use Modules\Settings\Models\Setting;

class ExpenseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        $currency = Setting::value('currency');

        return $schema
            ->components([
                TextEntryComponent::make('category.name')
                    ->label('Category'),

                TextEntryComponent::make('amount')
                    ->money($currency),

                TextEntryComponent::make('status')
                    ->colors([
                        'warning' => 'Pending',
                        'success' => 'Approved',
                        'danger'  => 'Rejected',
                    ])
                    ->badge(),

                TextEntryComponent::make('expense_date')
                    ->date(),

                RepeatableEntry::make('documents_images')
                    ->label('Documents Images')
                    ->state(fn ($record) => collect($record->documents ?? [])
                        ->filter(fn ($file) => preg_match('/\.(jpg|jpeg|png|webp)$/i', $file))
                        ->map(fn ($file) => [
                            'url'  => asset('storage/' . $file),
                            'name' => basename($file),
                        ])
                        ->values()
                        ->toArray()
                    )
                    ->schema([
                        ImageEntry::make('url')
                            ->label('')
                            ->square()
                            ->size(180)
                            ->extraImgAttributes([
                                'class' => 'cursor-pointer hover:opacity-80 transition',
                            ])
                            ->action(
                                MediaAction::make('view')
                                    ->media(fn ($state) => $state)
                                    ->modalHeading('View Expense Document')
                                    ->slideOver()
                            ),

                        TextEntry::make('name')
                            ->label('File Name')
                            ->color('gray'),
                    ])
                    ->grid(2)
                    ->columnSpanFull(),

                Section::make('Documents Files')
                    ->schema([
                        TextEntry::make('documents_files')
                            ->label('Files')
                            ->state(fn ($record) => collect($record->documents ?? [])
                                ->filter(fn ($file) => preg_match('/\.(pdf|doc|docx)$/i', $file))
                                ->map(fn ($file) => sprintf(
                                    '<a href="%s" target="_blank"
                        class="text-primary-600 hover:text-primary-800 hover:underline font-medium block mb-1">
                        %s
                    </a>',
                                    asset('storage/' . $file),
                                    basename($file)
                                ))
                                ->join('')
                            )
                            ->html()
                            ->placeholder('No documents available'),
                    ])
                    ->columnSpanFull(),

                TextEntry::make('description'),

                TextEntry::make('submittedBy.name')
                    ->label('Submitted By'),

                TextEntry::make('approvedBy.name')
                    ->label('Approved By'),
            ]);
    }
}
