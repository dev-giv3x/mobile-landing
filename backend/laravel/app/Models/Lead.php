<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'status',
        'created_at',
    ];

    protected function phone(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => preg_replace('/\D/', '', $value),
            get: fn (string $value) => $this->formatToMask($value),
        );
    }

    private function formatToMask($value) {
        if (!$value) return '';
        return preg_replace('/(\d{1})(\d{3})(\d{3})(\d{2})(\d{2})/', '+$1 ($2) $3-$4-$5', $value);
    }
}
