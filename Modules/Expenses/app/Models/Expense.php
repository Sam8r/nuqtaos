<?php

namespace Modules\Expenses\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Expenses\Database\Factories\ExpenseFactory;

class Expense extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'amount',
        'description',
        'expense_date',
        'status',
        'documents',
        'submitted_by',
        'approved_by',
        'expense_category_id',
    ];

    protected $casts = [
        'documents' => 'array',
        'expense_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    // protected static function newFactory(): ExpenseFactory
    // {
    //     // return ExpenseFactory::new();
    // }
}
