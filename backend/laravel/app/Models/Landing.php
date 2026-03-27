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

    public function getPrimaryColorAttribute(): string
    {
        return $this->settings['primary_color'] ?? '#1D65C1';
    }

    public function setPrimaryColorAttribute(string $value): void
    {
        $settings = $this->settings;
        $settings['primary_color'] = $value;
        $this->settings = $settings;
    }

    public function getLogoAttribute()
    {
        return $this->settings['logo'] ?? null;
    }

    public function setLogoAttribute($value): void
    {
        $settings = $this->settings;
        $settings['logo'] = $value;
        $this->settings = $settings;
    }

    public function getHeroImageAttribute()
    {
        return $this->content['hero']['image'] ?? null;
    }

    public function setHeroImageAttribute($value): void
    {
        $content = $this->content;
        $content['hero']['image'] = $value;
        $this->content = $content;
    }

    public function getHeroTitleAttribute()
    {
        return $this->content['hero']['title'] ?? null;
    }

    public function setHeroTitleAttribute($value): void
    {
        $content = $this->content;
        $content['hero']['title'] = $value;
        $this->content = $content;
    }

    protected static function booted(): void
    {
        static::creating(function (self $landing): void {
            if (auth()->check()) {
                $landing->moonshine_user_id = auth()->id();
            }
        });
    }
}
