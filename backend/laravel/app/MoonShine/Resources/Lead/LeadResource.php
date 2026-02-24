<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Lead;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lead;
use App\MoonShine\Resources\Lead\Pages\LeadIndexPage;
use App\MoonShine\Resources\Lead\Pages\LeadFormPage;
use App\MoonShine\Resources\Lead\Pages\LeadDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Support\Enums\Action;
use MoonShine\Support\ListOf;


/**
 * @extends ModelResource<Lead, LeadIndexPage, LeadFormPage, LeadDetailPage>
 */
class LeadResource extends ModelResource
{

    protected bool $withPolicy = true;
    protected string $model = Lead::class;

    protected string $title = 'Leads';

    protected bool $simplePaginate = true;


    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            LeadIndexPage::class,
            LeadFormPage::class,
            LeadDetailPage::class,
        ];
    }

}
