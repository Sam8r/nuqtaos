<?php

namespace Modules\Positions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Departments\Models\Department;
use Spatie\Translatable\HasTranslations;

// use Modules\Positions\Database\Factories\PositionFactory;

class Position extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'department_id',
    ];

    /**
     * The attributes that are translatable.
     */
    public $translatable = [
        'name',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    // protected static function newFactory(): PositionFactory
    // {
    //     // return PositionFactory::new();
    // }
}
