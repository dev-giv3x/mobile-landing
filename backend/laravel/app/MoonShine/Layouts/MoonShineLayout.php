<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use App\MoonShine\Pages\LogsPage;
use App\MoonShine\Pages\StatisticsPage;
use App\MoonShine\Resources\Landing\LandingResource;
use App\MoonShine\Resources\Lead\LeadResource;
use App\MoonShine\Resources\Lead\MyLeadResource;
use App\MoonShine\Resources\MoonShineUser\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRole\MoonShineUserRoleResource;
use MoonShine\AssetManager\Js;
use MoonShine\ColorManager\Palettes\PurplePalette;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Contracts\ColorManager\PaletteContract;
use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\MenuManager\MenuGroup;
use MoonShine\MenuManager\MenuItem;
use MoonShine\Components\Raw;


final class MoonShineLayout extends AppLayout
{
    /**
     * @var null|class-string<PaletteContract>
     */
    protected ?string $palette = PurplePalette::class;

    protected function assets(): array
    {
        return [
            ...parent::assets(),
            Js::make('https://cdnjs.cloudflare.com/ajax/libs/pusher/8.3.0/pusher.min.js'),
            Js::make('https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js'),
            Js::make('/js/manager_notification.js'),
        ];
    }

    public function components(): array
    {
        return [
            ...parent::components(),

            Raw::make('
            <script>
                window.reverbConfig = {
                    key: "'.config('broadcasting.connections.reverb.key').'",
                    wsHost: "'.config('broadcasting.connections.reverb.options.host').'",
                    wsPort: "'.config('broadcasting.connections.reverb.options.port').'"
                };
            </script>
        ')
        ];
    }

    protected function menu(): array
    {
        return [
            MenuGroup::make('Система', [
                MenuItem::make(MoonShineUserResource::class),
                MenuItem::make(MoonShineUserRoleResource::class),
                MenuItem::make(LogsPage::class, 'Логи'),
            ])->canSee(fn () => $this->isAdmin()),
            MenuItem::make(LandingResource::class, 'Лендинги'),
            MenuItem::make(LeadResource::class, 'Заявки'),
            MenuItem::make(MyLeadResource::class, 'Мои заявки')
                ->canSee(fn () => $this->isManager()),
            MenuItem::make(StatisticsPage::class, 'Статистика')
                ->canSee(fn () => $this->isManager()),
        ];
    }

    protected function getFooterMenu(): array
    {
        return [];
    }

    protected function getFooterCopyright(): string
    {
        return '';
    }

    /**
     * @param ColorManagerContract $colorManager
     */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);
    }

    private function isAdmin(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return $user->isSuperUser() || $user->moonshineUserRole?->name === 'Admin';
    }

    private function isManager(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return ! $this->isAdmin();
    }
}
