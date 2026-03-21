<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use MoonShine\Laravel\Models\MoonshineUser;

final class LeadStatistics
{
    public const PERIOD_TODAY = 'today';
    public const PERIOD_WEEK = 'week';
    public const PERIOD_ALL = 'all';

    /**
     * @return array<string, string>
     */
    public static function periods(): array
    {
        return [
            self::PERIOD_TODAY => 'За сегодня',
            self::PERIOD_WEEK => 'За неделю',
            self::PERIOD_ALL => 'За все время',
        ];
    }

    public static function closedCount(string $period = self::PERIOD_TODAY, ?int $managerId = null): int
    {
        $query = Lead::query()->where('status', 'closed');

        if ($managerId !== null) {
            $query->where('moonshine_user_id', $managerId);
        }

        self::applyPeriod($query, $period, 'closed_at');

        return $query->count();
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function topManagers(string $period = self::PERIOD_TODAY, int $limit = 10): Collection
    {
        $query = MoonshineUser::query()
            ->select('moonshine_users.id', 'moonshine_users.name')
            ->selectRaw('COUNT(leads.id) as closed_count')
            ->leftJoin('moonshine_user_roles', 'moonshine_user_roles.id', '=', 'moonshine_users.moonshine_user_role_id')
            ->join('leads', 'leads.moonshine_user_id', '=', 'moonshine_users.id')
            ->where('leads.status', 'closed')
            ->where(function (Builder $query): void {
                $query
                    ->whereNull('moonshine_user_roles.name')
                    ->orWhere('moonshine_user_roles.name', '!=', 'Admin');
            })
            ->groupBy('moonshine_users.id', 'moonshine_users.name')
            ->orderByDesc('closed_count')
            ->limit($limit);

        self::applyPeriod($query, $period, 'leads.closed_at');

        return $query
            ->get()
            ->map(static fn (MoonshineUser $user): array => [
                'id' => $user->id,
                'name' => $user->name,
                'closed_count' => (int) ($user->closed_count ?? 0),
            ]);
    }

    /**
     * @return array<string, int>
     */
    public static function managerStatusCounts(int $managerId): array
    {
        $counts = Lead::query()
            ->selectRaw('status, COUNT(*) as total')
            ->where('moonshine_user_id', $managerId)
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'new' => (int) ($counts['new'] ?? 0),
            'in_process' => (int) ($counts['in_process'] ?? 0),
            'closed' => (int) ($counts['closed'] ?? 0),
            'all' => (int) $counts->sum(),
        ];
    }

    private static function applyPeriod(Builder $query, string $period, string $column): void
    {
        $from = match ($period) {
            self::PERIOD_TODAY => Carbon::now()->startOfDay(),
            self::PERIOD_WEEK => Carbon::now()->startOfWeek(),
            default => null,
        };

        if ($from !== null) {
            $query->whereNotNull($column)->where($column, '>=', $from);
        }
    }
}
