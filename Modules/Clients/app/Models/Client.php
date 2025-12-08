<?php

namespace Modules\Clients\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\InteractsWithMedia;

// use Modules\Clients\Database\Factories\ClientFactory;

class Client extends Model
{
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_name_en',
        'company_name_ar',
        'contact_person_details',
        'address',
        'tax_number',
        'credit_limit',
        'payment_terms',
        'industry_type',
        'status',
        'tier',
        'geographic_location',
    ];

    /**
     * Get the emails for the client.
     */
    public function emails()
    {
        return $this->hasMany(ClientEmail::class);
    }

    /**
     * Get the phones for the client.
     */
    public function phones()
    {
        return $this->hasMany(ClientPhone::class);
    }
}
