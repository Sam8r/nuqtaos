<?php

namespace Modules\Leaves\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Employees\Models\Employee;

// use Modules\Leaves\Database\Factories\LeaveRequestFactory;

class LeaveRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'selected_dates',
        'total_days',
        'type',
        'status',
        'reason',
        'rejection_reason',
        'is_encashed',
        'employee_id',
    ];

    protected $casts = [
        'selected_dates' => 'array',
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            $model->total_days = is_array($model->selected_dates) ? count($model->selected_dates) : 0;
        });
    }

    /**
     * Get the employee that owns the leave request.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // protected static function newFactory(): LeaveRequestFactory
    // {
    //     // return LeaveRequestFactory::new();
    // }
}
