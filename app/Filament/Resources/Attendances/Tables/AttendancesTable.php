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
                    ->label(__('attendances.fields.date'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('employee.name')
                    ->label(__('attendances.fields.employee_name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('check_in')
                    ->label(__('attendances.fields.check_in'))
                    ->sortable(),
                TextColumn::make('check_out')
                    ->label(__('attendances.fields.check_out'))
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('period')
                    ->label(__('attendances.filters.period'))
                    ->options([
                        'today' => __('attendances.filters.today'),
                        'yesterday' => __('attendances.filters.yesterday'),
                        'this_week' => __('attendances.filters.this_week'),
                        'last_week' => __('attendances.filters.last_week'),
                        'this_month' => __('attendances.filters.this_month'),
                        'last_month' => __('attendances.filters.last_month'),
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
                        DatePicker::make('from')->label(__('attendances.filters.from')),
                        DatePicker::make('to')->label(__('attendances.filters.to')),
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
