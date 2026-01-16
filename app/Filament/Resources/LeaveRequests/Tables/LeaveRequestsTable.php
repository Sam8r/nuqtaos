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
                    ->label(__('leave_requests.fields.employee'))
                    ->searchable(),

                TextColumn::make('type')
                    ->label(__('leave_requests.fields.type'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => __('leave_requests.types.' . strtolower($state))),

                TextColumn::make('total_days')
                    ->label(__('leave_requests.fields.total_days'))
                    ->sortable(),

                TextColumn::make('status')
                    ->label(__('leave_requests.fields.status'))
                    ->badge()
                    ->colors([
                        'warning' => 'Pending',
                        'success' => 'Approved',
                        'danger'  => 'Rejected',
                    ])
                    ->formatStateUsing(fn ($state) => __('leave_requests.statuses.' . $state)),

                IconColumn::make('is_encashed')
                    ->label(__('leave_requests.fields.encashed'))
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label(__('leave_requests.fields.created_at'))
                    ->date(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('period')
                    ->label(__('leave_requests.filters.period'))
                    ->options(
                        collect(__('leave_requests.periods'))
                            ->mapWithKeys(fn ($label, $value) => [$value => $label])
                            ->toArray()
                    )
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
                    ->label(__('leave_requests.filters.status'))
                    ->options([
                        'Pending' => __('leave_requests.statuses.Pending'),
                        'Approved' => __('leave_requests.statuses.Approved'),
                        'Rejected' => __('leave_requests.statuses.Rejected'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when($data['value'], fn($query, $value) => $query->where('status', $value));
                    }),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label(__('leave_requests.actions.view')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('leave_requests.actions.delete')),
                ]),
            ]);
    }
}
