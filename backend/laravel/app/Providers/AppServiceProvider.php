<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Landing;
use App\Models\Lead;
use App\Observers\ActivityLogObserver;
use App\Policies\LeadPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use MoonShine\Laravel\Models\MoonshineUser;
use MoonShine\Laravel\Models\MoonshineUserRole;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Lead::class, LeadPolicy::class);

        Lead::observe(ActivityLogObserver::class);
        Landing::observe(ActivityLogObserver::class);
        MoonshineUser::observe(ActivityLogObserver::class);
        MoonshineUserRole::observe(ActivityLogObserver::class);
    }
}
