<?php

namespace Modules\Leaves\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Leaves\Database\Factories\LeaveBalanceFactory;

class LeaveBalance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'current_balance',
        'accumulated_balance',
        'year',
        'month',
        'employee_id',
    ];

    // protected static function newFactory(): LeaveBalanceFactory
    // {
    //     // return LeaveBalanceFactory::new();
    // }
}
