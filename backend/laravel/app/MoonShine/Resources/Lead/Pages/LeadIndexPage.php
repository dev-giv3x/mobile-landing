<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Lead\Pages;

use App\MoonShine\Resources\Lead\LeadResource;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;

/**
 * @extends IndexPage<LeadResource>
 */
class LeadIndexPage extends IndexPage
{
    protected function filters(): iterable
    {
        return [
            Select::make('Статус', 'status')
                ->options([
                    'new' => 'Новый',
                    'in_process' => 'В работе',
                    'closed' => 'Закрыт',
                ]),
        ];
    }

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
                ->sortable()
                ->badge(fn ($status) => $status === 'new' ? 'warning' : ($status === 'in_process' ? 'info' : 'success')),
            Date::make('Дата создания', 'created_at')->sortable(),
        ];
    }

    protected function buttons(): ListOf
    {
        $buttons = parent::buttons();

        $buttons->prepend(
            ActionButton::make('Принять', fn ($item) => route('moonshine.leads.accept', $item->getKey()))
                ->icon('check')
                ->success()
                ->canSee(fn ($item) => $this->isManager() && ($item?->status ?? null) === 'new')
        );

        return $buttons;
    }

    private function isManager(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return ! ($user->isSuperUser() || $user->moonshineUserRole?->name === 'Admin');
    }
}
