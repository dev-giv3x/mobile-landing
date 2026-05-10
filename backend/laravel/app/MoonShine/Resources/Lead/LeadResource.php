<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Lead;

use App\Models\Lead;
use App\MoonShine\Resources\Lead\Pages\LeadDetailPage;
use App\MoonShine\Resources\Lead\Pages\LeadFormPage;
use App\MoonShine\Resources\Lead\Pages\LeadIndexPage;
use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<Lead, LeadIndexPage, LeadFormPage, LeadDetailPage>
 */
class LeadResource extends ModelResource
{
    protected bool $withPolicy = true;
    protected string $model = Lead::class;

    protected string $title = 'Заявки';

    protected bool $simplePaginate = true;

    protected function pages(): array
    {
        return [
            LeadIndexPage::class,
            LeadFormPage::class,
            LeadDetailPage::class,
        ];
    }

    protected function modifyQueryBuilder(BuilderContract $builder): BuilderContract
    {
        if (! $this->isAdmin()) {
            return $builder->where('status', 'new');
        }

        return $builder;
    }

    private function isAdmin(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return $user->isSuperUser() || $user->moonshineUserRole?->name === 'Admin';
    }
}