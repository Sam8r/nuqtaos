<?php

namespace App\Filament\Resources\Attendances\Tables;

use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label('Date')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('employee.name')
                    ->label('Employee')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('check_in')
                    ->label('Check-In Time')
                    ->sortable(),
                TextColumn::make('check_out')
                    ->label('Check-Out Time')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('period')
                    ->label('Filter by Period')
                    ->options([
                        'today' => 'Today',
                        'yesterday' => 'Yesterday',
                        'this_week' => 'This Week',
                        'last_week' => 'Last Week',
                        'this_month' => 'This Month',
                        'last_month' => 'Last Month',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['value'], function (Builder $query, $value) {
                            return match ($value) {
                                'today' => $query->whereDate('date', Carbon::today()),
                                'yesterday' => $query->whereDate('date', Carbon::yesterday()),
                                'this_week' => $query->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]),
                                'last_week' => $query->whereBetween('date', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]),
                                'this_month' => $query->whereMonth('date', Carbon::now()->month)
                                    ->whereYear('date', Carbon::now()->year),
                                'last_month' => $query->whereMonth('date', Carbon::now()->subMonth()->month)
                                    ->whereYear('date', Carbon::now()->subMonth()->year),
                                default => $query,
                            };
                        });
                    }),

                Filter::make('custom_date')
                    ->form([
                        DatePicker::make('from')->label('From Date'),
                        DatePicker::make('to')->label('To Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('date', '>=', $data['from']))
                            ->when($data['to'], fn ($q) => $q->whereDate('date', '<=', $data['to']));
                    })
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([]),
            ]);
    }
}
