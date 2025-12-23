<?php

namespace Modules\Employees\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Departments\Models\Department;
use Modules\Positions\Models\Position;
use Spatie\Translatable\HasTranslations;

// use Modules\Employees\Database\Factories\EmployeeFactory;

class Employee extends Model
{
    use HasFactory, HasTranslations;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'emergency_contact',
        'joining_date',
        'contract_type',
        'status',
        'documents',
        'position_id',
        'department_id',
    ];

    /**
     * The attributes that are translatable.
     */
    public $translatable = [
        'name',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'name' => 'array',
        'documents' => 'array',
    ];

    /**
     * Get the position that owns the employee.
     */
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Get the department that owns the employee.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    // protected static function newFactory(): EmployeeFactory
    // {
    //     // return EmployeeFactory::new();
    // }
}
