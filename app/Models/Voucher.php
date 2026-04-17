<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'discount_percent',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'expires_at' => 'date',
        'is_active' => 'boolean',
    ];

    protected function code(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => strtoupper($value),
        );
    }
}
