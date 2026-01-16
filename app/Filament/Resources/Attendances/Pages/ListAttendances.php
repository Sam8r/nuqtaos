<?php

namespace App\Filament\Resources\Attendances\Pages;

use App\Filament\Resources\Attendances\AttendanceResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Modules\Attendances\Models\Attendance;
use Modules\Settings\Models\Setting;
use Carbon\Carbon;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    /**
     * Calculate distance between two points (Haversine formula)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // in meters
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('toggleAttendance')
                ->label(function () {
                    $user = auth()->user();
                    $attendance = Attendance::where('employee_id', $user->employee->id)
                        ->where('date', now()->toDateString())
                        ->first();

                    if (! $attendance || ! $attendance->check_in) {
                        return __('attendances.buttons.check_in');
                    }

                    if (! $attendance->check_out) {
                        return __('attendances.buttons.check_out');
                    }

                    return __('attendances.buttons.completed');
                })
                ->color(function () {
                    $user = auth()->user();
                    $attendance = Attendance::where('employee_id', $user->employee->id)
                        ->where('date', now()->toDateString())
                        ->first();

                    if (! $attendance || ! $attendance->check_in) {
                        return __('attendances.statuses.success');
                    }

                    if (! $attendance->check_out) {
                        return __('attendances.statuses.warning');
                    }

                    return __('attendances.statuses.secondary');
                })
                ->disabled(function () {
                    $user = auth()->user();
                    $attendance = Attendance::where('employee_id', $user->employee->id)
                        ->where('date', now()->toDateString())
                        ->first();
                    return $attendance && $attendance->check_in && $attendance->check_out;
                })
                ->alpineClickHandler(
                    sprintf(
                        "window.navigator.geolocation.getCurrentPosition(
                            (position) => {
                                \$wire.mountAction('toggleAttendance', {
                                    lat: position.coords.latitude,
                                    lng: position.coords.longitude
                                });
                            },
                            (error) => {
                                new FilamentNotification()
                                    .title('%s')
                                    .body('%s')
                                    .danger()
                                    .send();
                            }
                        )",
                        __('attendances.notifications.location_denied_title'),
                        __('attendances.notifications.location_denied_body')
                    )
                )
                ->action(function (array $arguments) {
                    $user = auth()->user();
                    $employee = $user->employee;
                    $settings = Setting::first();

                    $userLat = $arguments['lat'] ?? null;
                    $userLon = $arguments['lng'] ?? null;

                    if ($employee->work_type === 'Onsite') {

                        $userLat = $arguments['lat'] ?? null;
                        $userLon = $arguments['lng'] ?? null;

                        if (! $userLat || ! $userLon) {
                            Notification::make()
                                ->title(__('attendances.notifications.gps_error_title'))
                                ->body(__('attendances.notifications.gps_error_body'))
                                ->danger()
                                ->send();
                            return;
                        }

                        $distance = $this->calculateDistance(
                            $settings->company_latitude,
                            $settings->company_longitude,
                            $userLat,
                            $userLon
                        );

                        if ($distance > $settings->radius_meter) {
                            Notification::make()
                                ->title(__('attendances.notifications.out_of_range_title'))
                                ->body(__('attendances.notifications.out_of_range_body', [
                                    'distance' => round($distance),
                                    'allowed' => $settings->radius_meter,
                                ]))
                                ->danger()
                                ->send();
                            return;
                        }
                    }

                    $attendance = Attendance::firstOrCreate([
                        'employee_id' => $employee->id,
                        'date' => now()->toDateString(),
                    ]);

                    if (! $attendance->check_in) {
                        $attendance->update(['check_in' => now()]);
                        Notification::make()
                            ->title(__('attendances.notifications.check_in_success_title'))
                            ->success()
                            ->send();
                    }
                    elseif (! $attendance->check_out) {
                        $workStart = Carbon::parse($employee->work_start ?? $settings->default_work_from);
                        $workEnd = Carbon::parse($employee->work_end ?? $settings->default_work_to);
                        $overtimeThreshold = $settings->overtime_minutes ?? 30;

                        $checkIn = Carbon::parse($attendance->check_in);
                        $checkOut = now();

                        $workedMinutes = max(0, $checkOut->diffInMinutes($checkIn));
                        $officialMinutes = max(0, $workEnd->diffInMinutes($workStart));
                        $extraMinutes = max(0, $workedMinutes - $officialMinutes);

                        $overtimeHours = ($extraMinutes >= $overtimeThreshold) ? ceil($extraMinutes / 60) : 0;

                        $attendance->update([
                            'check_out' => $checkOut,
//                            'working_minutes' => $workedMinutes,
                            'total_working_hours' => round($workedMinutes / 60, 2),
                            'break_duration' => $settings->break_minutes,
                            'overtime_hours' => $overtimeHours,
                            'overtime_eligible' => $overtimeHours > 0,
                        ]);

                        Notification::make()
                            ->title(__('attendances.notifications.check_out_success_title'))
                            ->body(__('attendances.notifications.check_out_success_body', [
                                'worked' => round($workedMinutes / 60, 2),
                                'overtime' => $overtimeHours,
                            ]))
                            ->success()
                            ->send();
                    }
                }),
        ];
    }
}
