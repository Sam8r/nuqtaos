<?php

namespace Modules\Clients\Models;

use App\Traits\Search\HasUniversalSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

// use Modules\Clients\Database\Factories\ClientFactory;

class Client extends Model
{
    use HasFactory, SoftDeletes, HasUniversalSearch, HasTranslations;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_name',
        'contact_person_details',
        'address',
        'tax_number',
        'registration_documents',
        'credit_limit',
        'payment_terms',
        'industry_type',
        'status',
        'tier',
        'country',
    ];

    protected $casts = [
        'registration_documents' => 'array',
    ];

    /**
     * The attributes that are translatable.
     */
    public $translatable = ['company_name', 'payment_terms', 'industry_type'];

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
