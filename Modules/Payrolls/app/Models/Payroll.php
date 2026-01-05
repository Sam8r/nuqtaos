<?php

namespace Modules\Payrolls\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Employees\Models\Employee;

// use Modules\Payrolls\Database\Factories\PayrollFactory;

class Payroll extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'month_year',
        'basic_salary',
        'daily_rate',
        'bonuses_total',
        'deductions_total',
        'net_salary',
        'notes',
        'employee_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    // protected static function newFactory(): PayrollFactory
    // {
    //     // return PayrollFactory::new();
    // }
}
