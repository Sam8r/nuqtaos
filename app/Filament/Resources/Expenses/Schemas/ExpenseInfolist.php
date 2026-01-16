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
                    ->label(__('expenses.fields.category')),

                TextEntryComponent::make('amount')
                    ->label(__('expenses.fields.amount'))
                    ->money($currency),

                TextEntryComponent::make('status')
                    ->label(__('expenses.fields.status'))
                    ->colors([
                        'warning' => 'Pending',
                        'success' => 'Approved',
                        'danger'  => 'Rejected',
                    ])
                    ->badge()
                    ->formatStateUsing(fn (string $state) => __('expenses.statuses.' . $state)),

                TextEntryComponent::make('expense_date')
                    ->label(__('expenses.fields.expense_date'))
                    ->date(),

                RepeatableEntry::make('documents_images')
                    ->label(__('expenses.fields.documents_images'))
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
                                    ->label(__('expenses.actions.view_document'))
                                    ->media(fn ($state) => $state)
                                    ->modalHeading(__('expenses.modals.view_document'))
                                    ->slideOver()
                            ),

                        TextEntry::make('name')
                            ->label(__('expenses.fields.file_name'))
                            ->color('gray'),
                    ])
                    ->grid(2)
                    ->columnSpanFull(),

                Section::make(__('expenses.sections.documents_files'))
                    ->schema([
                        TextEntry::make('documents_files')
                            ->label(__('expenses.fields.documents_files'))
                            ->state(fn ($record) => collect($record->documents ?? [])
                                ->filter(fn ($file) => preg_match('/\.(pdf|doc|docx)$/i', $file))
                                ->map(fn ($file) => __(
                                    'expenses.messages.document_link',
                                    [
                                        'url' => asset('storage/' . $file),
                                        'name' => basename($file),
                                    ]
                                ))
                                ->join('')
                            )
                            ->html()
                            ->placeholder(__('expenses.messages.no_documents')),
                    ])
                    ->columnSpanFull(),

                TextEntry::make('description')
                    ->label(__('expenses.fields.description')),

                TextEntry::make('submittedBy.name')
                    ->label(__('expenses.fields.submitted_by')),

                TextEntry::make('approvedBy.name')
                    ->label(__('expenses.fields.approved_by')),
            ]);
    }
}
