<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'title',
        'content',
        'primary_icon',
        'secondary_icon',
        'secondary_content',
        'sort_order',
    ];

    protected static function booted()
    {
        static::saved(fn () => cache()->forget('landing_modules'));
        static::deleted(fn () => cache()->forget('landing_modules'));
    }
}
