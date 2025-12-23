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
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('phone'),
                TextColumn::make('position.name')
                    ->label('Position'),
                TextColumn::make('department.name')
                    ->label('Department'),
                TextColumn::make('status'),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                    'terminated' => 'Terminated',
                    'on_leave' => 'On Leave',
                ]),
                SelectFilter::make('contract_type')->options([
                    'full_time' => 'Full Time',
                    'part_time' => 'Part Time',
                    'Contract' => 'Contract',
                    'intern' => 'Intern',
                ]),
                SelectFilter::make('department')->relationship('department', 'name'),
                SelectFilter::make('position')->relationship('position', 'name'),
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
