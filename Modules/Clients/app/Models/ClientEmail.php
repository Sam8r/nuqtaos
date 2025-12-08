<?php

namespace Modules\Clients\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Clients\Database\Factories\ClientEmailFactory;

class ClientEmail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'email',
        'client_id',
    ];

    /**
     * Get the client that owns the email.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
