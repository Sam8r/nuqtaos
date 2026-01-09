<?php

namespace Modules\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Employees\Models\Employee;
use Modules\Projects\Models\Project;

// use Modules\Tasks\Database\Factories\TaskFactory;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'due_date',
        'employee_id',
        'project_id',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    /**
     * Get the employee that owns the task.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Get the project that owns the task.
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    // protected static function newFactory(): TaskFactory
    // {
    //     // return TaskFactory::new();
    // }
}
