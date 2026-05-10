<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Lead\Pages;

use App\MoonShine\Resources\Lead\LeadResource;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;

/**
 * @extends DetailPage<LeadResource>
 */
class LeadDetailPage extends DetailPage
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
            Text::make('Менеджер', 'moonshineUser.name'),
            Date::make('Дата создания', 'created_at')->format('d.m.Y H:i'),
        ];
    }
}