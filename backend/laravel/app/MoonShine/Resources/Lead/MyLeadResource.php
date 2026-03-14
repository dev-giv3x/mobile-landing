<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Lead;

use App\Models\Lead;
use App\MoonShine\Resources\Lead\Pages\LeadDetailPage;
use App\MoonShine\Resources\Lead\Pages\LeadFormPage;
use App\MoonShine\Resources\Lead\Pages\MyLeadIndexPage;
use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<Lead, MyLeadIndexPage, LeadFormPage, LeadDetailPage>
 */
class MyLeadResource extends ModelResource
{
    protected bool $withPolicy = true;
    protected string $model = Lead::class;
    protected string $title = 'Мои заявки';
    protected bool $simplePaginate = true;

    protected function pages(): array
    {
        return [
            MyLeadIndexPage::class,
            LeadFormPage::class,
            LeadDetailPage::class,
        ];
    }

    protected function modifyQueryBuilder(BuilderContract $builder): BuilderContract
    {
        $user = auth()->user();

        if (! $user || $this->isAdmin()) {
            return $builder->whereRaw('1 = 0');
        }

        return $builder
            ->where('moonshine_user_id', $user->id)
            ->whereIn('status', ['in_process', 'closed']);
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
