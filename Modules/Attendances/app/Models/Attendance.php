<?php

namespace Modules\Attendances\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Employees\Models\Employee;

// use Modules\Attendances\Database\Factories\AttendanceLogFactory;

class Attendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'date',
        'check_in',
        'check_out',
        'break_duration',
        'total_working_hours',
        'overtime_hours',
        'employee_id',
    ];

    /**
     * Get the employee that owns the attendance log.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // protected static function newFactory(): AttendanceLogFactory
    // {
    //     // return AttendanceLogFactory::new();
    // }
}
