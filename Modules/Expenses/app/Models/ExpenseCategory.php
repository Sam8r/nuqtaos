<?php

namespace Modules\Expenses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

// use Modules\Expenses\Database\Factories\ExpenseCategoryFactory;

class ExpenseCategory extends Model
{
    use HasFactory, HasTranslations;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'parent_id',
    ];

    /**
     * The attributes that are translatable.
     */
    public $translatable = ['name'];

    public function parent()
    {
        return $this->belongsTo(ExpenseCategory::class, 'parent_id');
    }

    public function subExpenseCategories()
    {
        return $this->hasMany(ExpenseCategory::class, 'parent_id');
    }
    // protected static function newFactory(): ExpenseCategoryFactory
    // {
    //     // return ExpenseCategoryFactory::new();
    // }
}
