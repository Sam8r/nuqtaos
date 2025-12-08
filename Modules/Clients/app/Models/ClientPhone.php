<?php

namespace Modules\Clients\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Clients\Database\Factories\ClientPhoneFactory;

class ClientPhone extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'phone',
        'client_id',
    ];

    /**
     * Get the client that owns the phone.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
