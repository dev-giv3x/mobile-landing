<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Landing;

use App\Models\Landing;
use App\MoonShine\Resources\Landing\Pages\LandingDetailPage;
use App\MoonShine\Resources\Landing\Pages\LandingFormPage;
use App\MoonShine\Resources\Landing\Pages\LandingIndexPage;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<Landing, LandingIndexPage, LandingFormPage, LandingDetailPage>
 */
class LandingResource extends ModelResource
{
    protected string $model = Landing::class;

    protected string $title = 'Лендинги';

    protected function pages(): array
    {
        return [
            LandingIndexPage::class,
            LandingFormPage::class,
            LandingDetailPage::class,
        ];
    }
}
