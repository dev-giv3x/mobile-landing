<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\ActivityLog;
use App\Support\ActivityLogger;
use Illuminate\Database\Eloquent\Model;

class ActivityLogObserver
{
    public function created(Model $model): void
    {
        if ($this->shouldSkip($model)) {
            return;
        }

        ActivityLogger::log(
            action: 'created',
            description: sprintf('Создана сущность %s #%s', $this->label($model), $model->getKey()),
            target: $model,
        );
    }

    public function updated(Model $model): void
    {
        if ($this->shouldSkip($model)) {
            return;
        }

        $changes = array_values(array_diff(array_keys($model->getChanges()), ['updated_at']));

        if ($changes === []) {
            return;
        }

        ActivityLogger::log(
            action: 'updated',
            description: $this->updatedDescription($model, $changes),
            target: $model,
            properties: ['changed' => $changes],
        );
    }

    public function deleted(Model $model): void
    {
        if ($this->shouldSkip($model)) {
            return;
        }

        ActivityLogger::log(
            action: 'deleted',
            description: sprintf('Удалена сущность %s #%s', $this->label($model), $model->getKey()),
            target: $model,
        );
    }

    private function shouldSkip(Model $model): bool
    {
        return $model instanceof ActivityLog;
    }

    /**
     * @param list<string> $changes
     */
    private function updatedDescription(Model $model, array $changes): string
    {
        if (class_basename($model) === 'Lead' && in_array('status', $changes, true)) {
            return sprintf('Изменен статус заявки #%s на "%s"', $model->getKey(), (string) $model->status);
        }

        return sprintf('Изменена сущность %s #%s', $this->label($model), $model->getKey());
    }

    private function label(Model $model): string
    {
        return match (class_basename($model)) {
            'Lead' => 'Заявка',
            'Landing' => 'Лендинг',
            'MoonshineUser' => 'Пользователь',
            'MoonshineUserRole' => 'Роль',
            default => class_basename($model),
        };
    }
}
