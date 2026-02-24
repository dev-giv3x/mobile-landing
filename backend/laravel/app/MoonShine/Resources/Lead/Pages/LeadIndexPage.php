<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Lead\Pages;

use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Contracts\UI\ComponentContract;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\QueryTags\QueryTag;
use MoonShine\UI\Components\Metrics\Wrapped\Metric;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Date;
use App\MoonShine\Resources\Lead\LeadResource;

use Throwable;



/**
 * @extends IndexPage<LeadResource>
 */
class LeadIndexPage extends IndexPage
{
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Имя', 'name'),
            Text::make('Телефон', 'phone'),
            Text::make('Почта', 'email'),
            Select::make('Статус', 'status')
                ->options([
                    'new' => 'Новый',
                    'in_process' => 'В работе',
                    'closed' => 'Закрыт',
                ])
                ->default('new')
                ->badge(fn($status) => $status === 'new' ? 'in_process' : 'closed'),

            Date::make('Дата создания', 'created_at')->sortable(),


        ];
    }

//    protected function filters(): iterable
//    {
//        return [
//            BelongsTo::make(
//                __('moonshine::ui.resource.role'),
//                'moonshineUserRole',
//                formatted: static fn (MoonshineUserRole $model) => $model->name,
//                resource: MoonShineUserRoleResource::class,
//            )->valuesQuery(static fn (Builder $q) => $q->select(['id', 'name'])),
//
//            Email::make('E-mail', 'email'),
//        ];
//    }

    /**
     * @param  TableBuilder  $component
     *
     * @return TableBuilder
     */

}
