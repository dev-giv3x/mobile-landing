<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Lead\Pages;

use App\MoonShine\Resources\Lead\MyLeadResource;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;

/**
 * @extends IndexPage<MyLeadResource>
 */
class MyLeadIndexPage extends IndexPage
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
                ->badge(fn ($status) => $status === 'new' ? 'warning' : ($status === 'in_process' ? 'info' : 'success')),
            Text::make('Лендинг', 'landing.title'),
            Date::make('Дата создания', 'created_at')->sortable(),
        ];
    }

    protected function buttons(): ListOf
    {
        $buttons = parent::buttons();

        $buttons->prepend(
            ActionButton::make('Закрыть', fn ($item) => $this->getResource()->getFormPageUrl($item->getKey()))
                ->icon('x-circle')
                ->warning()
                ->canSee(fn ($item) => ($item?->status ?? null) !== 'closed')
        );

        return $buttons;
    }
}
