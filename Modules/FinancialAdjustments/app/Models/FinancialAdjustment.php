<?php

namespace Modules\FinancialAdjustments\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\FinancialAdjustments\Database\Factories\FinancialAdjustmentFactory;

class FinancialAdjustment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'type',
        'category',
        'amount',
        'days_count',
        'reason',
        'status',
        'processing_date',
        'employee_id',
    ];

    // protected static function newFactory(): FinancialAdjustmentFactory
    // {
    //     // return FinancialAdjustmentFactory::new();
    // }
}
