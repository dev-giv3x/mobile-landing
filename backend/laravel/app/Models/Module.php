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
}
