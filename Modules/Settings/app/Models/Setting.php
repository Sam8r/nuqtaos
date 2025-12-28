<?php

namespace Modules\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Settings\Database\Factories\SettingFactory;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'logo',
        'phone',
        'email',
        'address',
        'tax_number',
        'currency',
        'tax',
        'default_printable_language',
    ];

    // protected static function newFactory(): SettingFactory
    // {
    //     // return SettingFactory::new();
    // }
}
