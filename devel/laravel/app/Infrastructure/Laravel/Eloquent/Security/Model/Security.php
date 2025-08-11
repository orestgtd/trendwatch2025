<?php

namespace App\Infrastructure\Laravel\Eloquent\Security\Model;

use Illuminate\Database\Eloquent\Model;

class Security extends Model
{
    protected $table = 'securities';

    protected $fillable = [
        'canonical_description',
        'security_number',
        'symbol',
        'variations',
        'unit_type',
        'expiration_date',
    ];

    protected $casts = [
        'variations'  => 'array',
    ];
}
