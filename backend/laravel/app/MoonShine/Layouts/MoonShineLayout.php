<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\ColorManager\Palettes\PurplePalette;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Contracts\ColorManager\PaletteContract;
use App\MoonShine\Resources\MoonShineUser\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRole\MoonShineUserRoleResource;
use App\MoonShine\Resources\Landing\LandingResource;
use MoonShine\MenuManager\MenuGroup;
use MoonShine\MenuManager\MenuItem;
use App\MoonShine\Resources\Lead\LeadResource;

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
        ];
    }

    protected function menu(): array
    {
           return [
               MenuGroup::make('Система', [
                   MenuItem::make(MoonShineUserResource::class),
                   MenuItem::make(MoonShineUserRoleResource::class),
               ])->canSee(fn() => auth()->user()?->isSuperUser()),
               MenuItem::make(LandingResource::class, 'Лендинги'),
               MenuItem::make(LeadResource::class, 'Заявки'),

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
     * @param ColorManager $colorManager
     */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);

        // $colorManager->primary('#00000');
    }
}
