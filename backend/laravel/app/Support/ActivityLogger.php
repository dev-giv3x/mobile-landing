<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

final class ActivityLogger
{
    /**
     * @param array<string, mixed> $properties
     */
    public static function log(string $action, string $description, Model|string|null $target = null, array $properties = []): void
    {
        $targetType = null;
        $targetId = null;

        if ($target instanceof Model) {
            $targetType = $target::class;
            $targetId = $target->getKey();
        }

        if (is_string($target)) {
            $targetType = $target;
        }

        ActivityLog::query()->create([
            'moonshine_user_id' => auth()->id(),
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'description' => $description,
            'properties' => $properties === [] ? null : $properties,
        ]);
    }
}
