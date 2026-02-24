<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Landing;

use Illuminate\Database\Eloquent\Model;
use App\Models\Landing;
use App\MoonShine\Resources\Landing\Pages\LandingIndexPage;
use App\MoonShine\Resources\Landing\Pages\LandingFormPage;
use App\MoonShine\Resources\Landing\Pages\LandingDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<Landing, LandingIndexPage, LandingFormPage, LandingDetailPage>
 */
class LandingResource extends ModelResource
{
    protected string $model = Landing::class;

    protected string $title = 'Landings';
    
    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            LandingIndexPage::class,
            LandingFormPage::class,
            LandingDetailPage::class,
        ];
    }
}
