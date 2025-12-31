<?php

namespace App\Filament\Resources\LeaveRequests\Tables;

use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LeaveRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.name')
                    ->label('Employee')
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge(),

                TextColumn::make('total_days')
                    ->label('Total Days')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'Pending',
                        'success' => 'Approved',
                        'danger'  => 'Rejected',
                    ]),

                IconColumn::make('is_encashed')
                    ->label('Encashed')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Requested At')
                    ->date(),
            ])
            ->defaultSort('created_at', 'desc')
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
                                'today' => $query->whereJsonContains('selected_dates', Carbon::today()->toDateString()),
                                'yesterday' => $query->whereJsonContains('selected_dates', Carbon::yesterday()->toDateString()),
                                'this_week' => $query->whereJsonContains('selected_dates', fn($date) =>
                                Carbon::parse($date)->isBetween(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek())
                                ),
                                default => $query,
                            };
                        });
                    }),

                SelectFilter::make('status')
                    ->label('Filter by Status')
                    ->options([
                        'Pending' => 'Pending',
                        'Approved' => 'Approved',
                        'Rejected' => 'Rejected',
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when($data['value'], fn($query, $value) => $query->where('status', $value));
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([]),
            ]);
    }
}
