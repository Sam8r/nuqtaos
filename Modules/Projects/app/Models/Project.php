<?php

namespace Modules\Projects\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Clients\Models\Client;
use Modules\Employees\Models\Employee;

// use Modules\Projects\Database\Factories\ProjectFactory;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'budget',
        'status',
        'progress_percentage',
        'client_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the client that owns the project.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function teamLeader()
    {
        return $this->belongsTo(Employee::class, 'team_leader_id');
    }

    // protected static function newFactory(): ProjectFactory
    // {
    //     // return ProjectFactory::new();
    // }
}
