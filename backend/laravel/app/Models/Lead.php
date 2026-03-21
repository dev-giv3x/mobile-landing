<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MoonShine\Laravel\Models\MoonshineUser;

class Lead extends Model
{
    protected $fillable = [
        'landing_id',
        'moonshine_user_id',
        'name',
        'phone',
        'email',
        'status',
        'closed_at',
        'created_at',
    ];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $lead): void {
            if ($lead->status === 'closed' && $lead->closed_at === null) {
                $lead->closed_at = now();
            }

            if ($lead->status !== 'closed') {
                $lead->closed_at = null;
            }
        });
    }

    protected function phone(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => preg_replace('/\D/', '', $value),
            get: fn (string $value) => $this->formatToMask($value),
        );
    }

    public function landing(): BelongsTo
    {
        return $this->belongsTo(Landing::class);
    }

    public function moonshineUser(): BelongsTo
    {
        return $this->belongsTo(MoonshineUser::class, 'moonshine_user_id');
    }

    private function formatToMask($value)
    {
        if (! $value) {
            return '';
        }

        return preg_replace('/(\d{1})(\d{3})(\d{3})(\d{2})(\d{2})/', '+$1 ($2) $3-$4-$5', $value);
    }
}
