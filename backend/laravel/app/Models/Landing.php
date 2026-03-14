<?php

namespace App\Models;

use App\Support\LandingTemplate;
use Illuminate\Database\Eloquent\Model;

class Landing extends Model
{
    protected $fillable = [
        'title',
        'company_name',
        'slug',
        'settings',
        'content',
        'moonshine_user_id',
    ];

    protected $casts = [
        'settings' => 'array',
        'content' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $landing): void {
            if (auth()->check()) {
                $landing->moonshine_user_id = auth()->id();
            }
        });
    }

//    public function getContentAttribute(mixed $value): array
//    {
//        $content = $this->decodeJson($value);
//
//        return LandingTemplate::normalizeContentForForm($this, $content);
//    }
//
//    public function getSettingsAttribute(mixed $value): array
//    {
//        $settings = $this->decodeJson($value);
//
//        return LandingTemplate::normalizeSettings($settings);
//    }

    private function decodeJson(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

//    protected $attributes = [
//        'settings' => '{}',
//        'content' => '{}',
//    ];
}
