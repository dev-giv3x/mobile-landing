<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Landing\Pages;

use App\MoonShine\Resources\Landing\LandingResource;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;

/**
 * @extends IndexPage<LandingResource>
 */
class LandingIndexPage extends IndexPage
{
    protected bool $isLazy = true;

    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Лендинг', 'title')->sortable(),
            Text::make('Компания', 'company_name')->sortable(),
            Text::make('Slug', 'slug')->badge('primary'),
            Date::make('Создан', 'created_at')->format('d.m.Y')->sortable(),
        ];
    }
}
