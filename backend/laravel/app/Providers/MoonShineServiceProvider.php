<?php

declare(strict_types=1);

namespace App\Providers;

use App\MoonShine\Pages\LogsPage;
use App\MoonShine\Pages\StatisticsPage;
use App\MoonShine\Resources\Landing\LandingResource;
use App\MoonShine\Resources\Lead\LeadResource;
use App\MoonShine\Resources\Lead\MyLeadResource;
use App\MoonShine\Resources\MoonShineUser\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRole\MoonShineUserRoleResource;
use Illuminate\Support\ServiceProvider;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Laravel\DependencyInjection\MoonShineConfigurator;

class MoonShineServiceProvider extends ServiceProvider
{
    /**
     * @param CoreContract<MoonShineConfigurator> $core
     */
    public function boot(CoreContract $core): void
    {
        $core
            ->resources([
                MoonShineUserResource::class,
                MoonShineUserRoleResource::class,
                LandingResource::class,
                LeadResource::class,
                MyLeadResource::class,
            ])
            ->pages([
                ...$core->getConfig()->getPages(),
                StatisticsPage::class,
                LogsPage::class,
            ]);
    }
}
