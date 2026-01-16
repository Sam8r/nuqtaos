<?php

namespace App\Filament\Resources\Departments\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubDepartmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'subDepartments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('name')
                    ->label(__('departments.relation.name'))
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->heading(__('departments.relation.table.heading'))
            ->searchPlaceholder(__('departments.relation.table.search'))
            ->columns([
                TextColumn::make('name')
                    ->label(__('departments.relation.name'))
                    ->searchable(),
            ])
            ->filters([
                TrashedFilter::make()
                    ->label(__('departments.filters.trashed')),
            ])
            ->emptyStateHeading(__('departments.relation.table.empty_heading'))
            ->emptyStateDescription(__('departments.relation.table.empty_description'))
            ->emptyStateActions([
                CreateAction::make('empty-state-create')
                    ->label(__('departments.relation.table.empty_action')),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label(__('departments.relation.actions.create')),
                AssociateAction::make()
                    ->label(__('departments.relation.actions.associate')),
            ])
            ->recordActions([
                EditAction::make()
                    ->label(__('departments.relation.actions.edit')),
                DissociateAction::make()
                    ->label(__('departments.relation.actions.dissociate')),
                DeleteAction::make()
                    ->label(__('departments.relation.actions.delete')),
                ForceDeleteAction::make()
                    ->label(__('departments.relation.actions.force_delete')),
                RestoreAction::make()
                    ->label(__('departments.relation.actions.restore')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make()
                        ->label(__('departments.relation.bulk_actions.dissociate')),
                    DeleteBulkAction::make()
                        ->label(__('departments.relation.bulk_actions.delete')),
                    ForceDeleteBulkAction::make()
                        ->label(__('departments.relation.bulk_actions.force_delete')),
                    RestoreBulkAction::make()
                        ->label(__('departments.relation.bulk_actions.restore')),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
