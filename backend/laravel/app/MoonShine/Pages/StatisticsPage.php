<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Support\LeadStatistics;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\MenuManager\Attributes\SkipMenu;
use MoonShine\UI\Components\FlexibleRender;

#[SkipMenu]
class StatisticsPage extends \MoonShine\Laravel\Pages\Page
{
    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle(),
        ];
    }

    public function getTitle(): string
    {
        return 'Статистика';
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

        if (! $user || $this->isAdmin($user)) {
            return '<div class="p-6 rounded-xl bg-white border border-gray-200">Статистика доступна только менеджерам.</div>';
        }

        $period = $this->normalizePeriod((string) request()->get('period', LeadStatistics::PERIOD_TODAY));
        $statusCounts = LeadStatistics::managerStatusCounts((int) $user->id);
        $periods = LeadStatistics::periods();
        $closedCount = LeadStatistics::closedCount($period, (int) $user->id);

        $buttons = collect($periods)
            ->map(fn (string $label, string $key): string => sprintf(
                '<a href="?period=%s" style="padding:8px 14px;border-radius:999px;border:1px solid %s;background:%s;color:%s;text-decoration:none;font-weight:600;">%s</a>',
                e($key),
                $key === $period ? '#1d4ed8' : '#d1d5db',
                $key === $period ? '#dbeafe' : '#ffffff',
                '#111827',
                e($label)
            ))
            ->implode(' ');

        return sprintf(
            '<div style="display:grid;gap:20px;">'
            . '<div style="padding:24px;border-radius:24px;background:#fff;border:1px solid #e5e7eb;">'
            . '<div style="display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;">'
            . '<div><div style="font-size:28px;font-weight:700;">Моя статистика</div><div style="color:#6b7280;margin-top:6px;">Текущая воронка и закрытые заявки по выбранному периоду.</div></div>'
            . '<div style="display:flex;gap:8px;flex-wrap:wrap;">%s</div>'
            . '</div>'
            . '</div>'
            . '<div style="display:grid;grid-template-columns:repeat(4, minmax(0, 1fr));gap:16px;">%s%s%s%s</div>'
            . '<div style="padding:24px;border-radius:24px;background:#fff;border:1px solid #e5e7eb;">'
            . '<div style="font-size:20px;font-weight:700;">Закрыто за период</div>'
            . '<div style="font-size:42px;font-weight:800;margin-top:12px;">%d</div>'
            . '<div style="color:#6b7280;margin-top:6px;">Период: %s</div>'
            . '</div>'
            . '</div>',
            $buttons,
            $this->card('Всего моих заявок', $statusCounts['all']),
            $this->card('Новые', $statusCounts['new']),
            $this->card('В работе', $statusCounts['in_process']),
            $this->card('Закрытые', $statusCounts['closed']),
            $closedCount,
            e($periods[$period])
        );
    }

    private function card(string $label, int $value): string
    {
        return sprintf(
            '<div style="padding:20px;border-radius:20px;background:#fff;border:1px solid #e5e7eb;">'
            . '<div style="color:#6b7280;font-size:14px;">%s</div>'
            . '<div style="font-size:34px;font-weight:800;margin-top:10px;">%d</div>'
            . '</div>',
            e($label),
            $value
        );
    }

    private function normalizePeriod(string $period): string
    {
        return array_key_exists($period, LeadStatistics::periods()) ? $period : LeadStatistics::PERIOD_TODAY;
    }

    private function isAdmin($user): bool
    {
        return $user->isSuperUser() || $user->moonshineUserRole?->name === 'Admin';
    }
}
