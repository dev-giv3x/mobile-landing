<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Lead\Pages;

use App\Models\Landing;
use App\MoonShine\Resources\Lead\LeadResource;
use Illuminate\Validation\Rule;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;

/**
 * @extends FormPage<LeadResource>
 */
class LeadFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Text::make('Имя', 'name')->readonly(),
                Text::make('Телефон', 'phone')->readonly(),
                Text::make('Почта', 'email')->readonly(),
                Select::make('Статус', 'status')
                    ->options([
                        'new' => 'Новый',
                        'in_process' => 'В работе',
                        'closed' => 'Закрыт',
                    ])
                    ->default('new')
                    ->required(),
                Select::make('Лендинг', 'landing_id')
                    ->options(fn () => $this->landingOptions())
                    ->searchable()
                    ->nullable(),
            ]),
        ];
    }

    protected function rules(DataWrapperContract $item): array
    {
        $user = auth()->user();
        $landingRule = Rule::exists('landings', 'id');

        if ($user && ! $this->isAdmin($user)) {
            $landingRule = $landingRule->where('moonshine_user_id', $user->id);
        }

        return [
            'status' => ['required', Rule::in(['new', 'in_process', 'closed'])],
            'landing_id' => ['nullable', 'required_if:status,closed', $landingRule],
        ];
    }

    /**
     * @return array<int|string, string>
     */
    private function landingOptions(): array
    {
        $query = Landing::query()->select(['id', 'title']);
        $user = auth()->user();

        if ($user && ! $this->isAdmin($user)) {
            $query->where('moonshine_user_id', $user->id);
        }

        return $query->orderBy('title')->pluck('title', 'id')->toArray();
    }

    private function isAdmin($user): bool
    {
        return $user && ($user->isSuperUser() || $user->moonshineUserRole?->name === 'Admin');
    }
}
