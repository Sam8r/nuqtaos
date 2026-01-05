<?php

namespace Modules\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Settings\Database\Factories\SettingFactory;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'logo',
        'phone',
        'email',
        'address',
        'tax_number',
        'currency',
        'salary_currency',
        'tax',
        'default_printable_language',
        'break_minutes',
        'overtime_minutes',
        'days_off_limit',
        'encashment_limit',
        'default_payroll_start_day',
        'weekends',
        'overtime_active_mode',
        'overtime_percentage',
        'overtime_fixed_rate',
        'default_work_from',
        'default_work_to',
        'grace_period_minutes',
        'work_type_days',
        'company_latitude',
        'company_longitude',
        'radius_meter',
        'default_leave_type',
    ];

    protected $casts = [
        'work_type_days' => 'array',
    ];

    // protected static function newFactory(): SettingFactory
    // {
    //     // return SettingFactory::new();
    // }
}
