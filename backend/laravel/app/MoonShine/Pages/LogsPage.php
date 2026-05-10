<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\ActivityLog;
use Illuminate\Support\Carbon;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\MenuManager\Attributes\SkipMenu;
use MoonShine\UI\Components\FlexibleRender;

#[SkipMenu]
class LogsPage extends \MoonShine\Laravel\Pages\Page
{
    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle(),
        ];
    }

    public function getTitle(): string
    {
        return 'Логи';
    }

    protected function components(): iterable
    {
        return [
            FlexibleRender::make(fn (): string => $this->renderContent()),
        ];
    }

    private function renderContent(): string
    {
        $user = auth()->user();

        if (! $user || ! $this->isAdmin($user)) {
            return '<div class="p-6 rounded-xl bg-white border border-gray-200">Логи доступны только администратору.</div>';
        }

        [$from, $to] = $this->resolvePeriod();

        $logs = ActivityLog::query()
            ->with('moonshineUser')
            ->when($from !== null, fn ($query) => $query->where('created_at', '>=', $from->copy()->startOfDay()))
            ->when($to !== null, fn ($query) => $query->where('created_at', '<=', $to->copy()->endOfDay()))
            ->orderByDesc('created_at')
            ->limit(200)
            ->get();

        $rows = $logs->map(function (ActivityLog $log): string {
            $changes = $log->properties['changed'] ?? [];
            $details = $changes === [] ? '—' : e(implode(', ', $changes));
            $userName = $log->moonshineUser?->name ?? 'Система';

            return sprintf(
                '<tr>'
                . '<td style="padding:12px;border-bottom:1px solid #e5e7eb;white-space:nowrap;">%s</td>'
                . '<td style="padding:12px;border-bottom:1px solid #e5e7eb;">%s</td>'
                . '<td style="padding:12px;border-bottom:1px solid #e5e7eb;">%s</td>'
                . '<td style="padding:12px;border-bottom:1px solid #e5e7eb;">%s</td>'
                . '<td style="padding:12px;border-bottom:1px solid #e5e7eb;">%s</td>'
                . '</tr>',
                e(optional($log->created_at)->format('d.m.Y H:i:s') ?? ''),
                e($userName),
                e($log->action),
                e($log->description),
                $details
            );
        })->implode('');

        return sprintf(
            '<div style="display:grid;gap:20px;">'
            . '<form method="GET" style="padding:24px;border-radius:24px;background:#fff;border:1px solid #e5e7eb;display:flex;gap:12px;align-items:end;flex-wrap:wrap;">'
            . '<div><div style="font-size:14px;color:#6b7280;margin-bottom:6px;">С даты</div><input type="date" name="from" value="%s" style="padding:10px 12px;border:1px solid #d1d5db;border-radius:12px;"></div>'
            . '<div><div style="font-size:14px;color:#6b7280;margin-bottom:6px;">По дату</div><input type="date" name="to" value="%s" style="padding:10px 12px;border:1px solid #d1d5db;border-radius:12px;"></div>'
            . '<button type="submit" style="padding:10px 16px;border:none;border-radius:12px;background:#111827;color:#fff;font-weight:600;">Применить</button>'
            . '<a href="?preset=today" style="padding:10px 16px;border-radius:12px;border:1px solid #d1d5db;color:#111827;text-decoration:none;">Сегодня</a>'
            . '<a href="?preset=week" style="padding:10px 16px;border-radius:12px;border:1px solid #d1d5db;color:#111827;text-decoration:none;">Неделя</a>'
            . '<a href="?preset=all" style="padding:10px 16px;border-radius:12px;border:1px solid #d1d5db;color:#111827;text-decoration:none;">Все время</a>'
            . '</form>'
            . '<div style="padding:24px;border-radius:24px;background:#fff;border:1px solid #e5e7eb;overflow:auto;">'
            . '<div style="font-size:20px;font-weight:700;margin-bottom:16px;">Журнал действий</div>'
            . '<table style="width:100%%;border-collapse:collapse;min-width:900px;">'
            . '<thead><tr style="text-align:left;background:#f9fafb;">'
            . '<th style="padding:12px;">Дата</th><th style="padding:12px;">Пользователь</th><th style="padding:12px;">Действие</th><th style="padding:12px;">Описание</th><th style="padding:12px;">Изменения</th>'
            . '</tr></thead><tbody>%s</tbody></table>'
            . '</div>'
            . '</div>',
            e($from?->format('Y-m-d') ?? ''),
            e($to?->format('Y-m-d') ?? ''),
            $rows === '' ? '<tr><td colspan="5" style="padding:20px;text-align:center;color:#6b7280;">Логи за выбранный период не найдены.</td></tr>' : $rows
        );
    }

    /**
     * @return array{0: Carbon|null, 1: Carbon|null}
     */
    private function resolvePeriod(): array
    {
        $preset = (string) request()->get('preset', '');

        if ($preset === 'today') {
            $today = Carbon::today();

            return [$today, $today];
        }

        if ($preset === 'week') {
            return [Carbon::now()->startOfWeek(), Carbon::now()];
        }

        if ($preset === 'all') {
            return [null, null];
        }

        $from = request()->filled('from') ? Carbon::parse((string) request()->get('from')) : null;
        $to = request()->filled('to') ? Carbon::parse((string) request()->get('to')) : null;

        return [$from, $to];
    }

    private function isAdmin($user): bool
    {
        return $user->isSuperUser() || $user->moonshineUserRole?->name === 'Admin';
    }
}
