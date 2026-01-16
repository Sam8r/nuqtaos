<?php

namespace App\Filament\Resources\Employees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('employees.fields.id'))
                    ->sortable(),
                TextColumn::make('name')
                    ->label(__('employees.fields.name'))
                    ->searchable(),
                TextColumn::make('user.email')
                    ->label(__('employees.fields.email'))
                    ->searchable(),
                TextColumn::make('phone')
                    ->label(__('employees.fields.phone')),
                TextColumn::make('position.name')
                    ->label(__('employees.fields.position')),
                TextColumn::make('department.name')
                    ->label(__('employees.fields.department')),
                TextColumn::make('status')
                    ->label(__('employees.fields.status'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => __('employees.statuses.' . $state)),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('employees.filters.status'))
                    ->options([
                        'Active' => __('employees.statuses.Active'),
                        'Inactive' => __('employees.statuses.Inactive'),
                        'Terminated' => __('employees.statuses.Terminated'),
                        'On Leave' => __('employees.statuses.On Leave'),
                    ]),
                SelectFilter::make('contract_type')
                    ->label(__('employees.filters.contract_type'))
                    ->options([
                        'Full Time' => __('employees.contract_types.Full Time'),
                        'Part Time' => __('employees.contract_types.Part Time'),
                        'Intern' => __('employees.contract_types.Intern'),
                    ]),
                SelectFilter::make('department')
                    ->label(__('employees.filters.department'))
                    ->relationship('department', 'name'),
                SelectFilter::make('position')
                    ->label(__('employees.filters.position'))
                    ->relationship('position', 'name'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([]),
            ]);
    }
}
