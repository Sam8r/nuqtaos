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

                    if (!$attendance || !$attendance->check_in) return 'Check In';
                    if (!$attendance->check_out) return 'Check Out';
                    return 'Completed';
                })
                ->color(function () {
                    $user = auth()->user();
                    $attendance = Attendance::where('employee_id', $user->employee->id)
                        ->where('date', now()->toDateString())
                        ->first();

                    if (!$attendance || !$attendance->check_in) return 'success';
                    if (!$attendance->check_out) return 'warning';
                    return 'secondary';
                })
                ->disabled(function () {
                    $user = auth()->user();
                    $attendance = Attendance::where('employee_id', $user->employee->id)
                        ->where('date', now()->toDateString())
                        ->first();
                    return $attendance && $attendance->check_in && $attendance->check_out;
                })
                ->alpineClickHandler(
                    "window.navigator.geolocation.getCurrentPosition(
                        (position) => {
                            \$wire.mountAction('toggleAttendance', {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            });
                        },
                        (error) => {
                            new FilamentNotification()
                                .title('Location Access Denied')
                                .body('Please enable GPS to record your attendance.')
                                .danger()
                                .send();
                        }
                    )"
                )
                ->action(function (array $arguments) {
                    $user = auth()->user();
                    $employee = $user->employee;
                    $settings = Setting::first();

                    $userLat = $arguments['lat'] ?? null;
                    $userLon = $arguments['lng'] ?? null;

                    if (!$userLat || !$userLon) {
                        Notification::make()->title('GPS Error')->body('Unable to retrieve location.')->danger()->send();
                        return;
                    }

                    // Validation based on dynamic coordinates and radius
                    $distance = $this->calculateDistance(
                        $settings->company_latitude,
                        $settings->company_longitude,
                        $userLat,
                        $userLon
                    );

                    if ($distance > $settings->radius_meter) {
                        Notification::make()
                            ->title('Out of Range')
                            ->body("Distance: " . round($distance) . "m. Allowed: " . $settings->radius_meter . "m.")
                            ->danger()
                            ->send();
                        return;
                    }

                    $attendance = Attendance::firstOrCreate([
                        'employee_id' => $employee->id,
                        'date' => now()->toDateString(),
                    ]);

                    if (!$attendance->check_in) {
                        $attendance->update(['check_in' => now()]);
                        Notification::make()->title('Check In recorded successfully')->success()->send();
                    }
                    elseif (!$attendance->check_out) {
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
                            'working_minutes' => $workedMinutes,
                            'total_working_hours' => round($workedMinutes / 60, 2),
                            'break_duration' => $settings->break_minutes,
                            'overtime_hours' => $overtimeHours,
                            'overtime_eligible' => $overtimeHours > 0,
                        ]);

                        Notification::make()
                            ->title('Check Out recorded successfully')
                            ->body("Worked: " . round($workedMinutes / 60, 2) . " hrs | Overtime: " . $overtimeHours)
                            ->success()
                            ->send();
                    }
                }),
        ];
    }
}
