<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Support\LeadStatistics;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\MenuManager\Attributes\SkipMenu;
use MoonShine\UI\Components\FlexibleRender;

#[SkipMenu]
class Dashboard extends \MoonShine\Laravel\Pages\Page
{
    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle(),
        ];
    }

    public function getTitle(): string
    {
        return $this->title ?: 'Информационная панель';
    }

    protected function components(): iterable
    {
        return [
            FlexibleRender::make(fn (): string => $this->renderDashboard()),
        ];
    }

    private function renderDashboard(): string
    {
        $closedPeriod = $this->normalizePeriod((string) request()->get('closed_period', LeadStatistics::PERIOD_TODAY));
        $managerPeriod = $this->normalizePeriod((string) request()->get('manager_period', LeadStatistics::PERIOD_TODAY));
        $periods = LeadStatistics::periods();
        $topManagers = LeadStatistics::topManagers($managerPeriod, 10);

        $managerRows = $topManagers->map(static fn (array $manager, int $index): string => sprintf(
            '<tr>'
            . '<td style="padding:12px;border-bottom:1px solid #e5e7eb;">%d</td>'
            . '<td style="padding:12px;border-bottom:1px solid #e5e7eb;">%s</td>'
            . '<td style="padding:12px;border-bottom:1px solid #e5e7eb;">%d</td>'
            . '</tr>',
            $index + 1,
            e($manager['name']),
            $manager['closed_count']
        ))->implode('');

        return sprintf(
            '<div style="display:grid;gap:24px;">'
            . '<div style="display:grid;grid-template-columns:repeat(3, minmax(0, 1fr));gap:16px;">%s%s%s</div>'
            . '<div style="padding:24px;border-radius:24px;background:#fff;border:1px solid #e5e7eb;">'
            . '<div style="display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;">'
            . '<div><div style="font-size:22px;font-weight:700;">Закрытые заявки</div><div style="color:#6b7280;margin-top:6px;">Сводка по всем менеджерам.</div></div>'
            . '<div style="display:flex;gap:8px;flex-wrap:wrap;">%s</div>'
            . '</div>'
            . '<div style="font-size:48px;font-weight:800;margin-top:20px;">%d</div>'
            . '<div style="color:#6b7280;margin-top:6px;">Период: %s</div>'
            . '</div>'
            . '<div style="padding:24px;border-radius:24px;background:#fff;border:1px solid #e5e7eb;overflow:auto;">'
            . '<div style="display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;">'
            . '<div><div style="font-size:22px;font-weight:700;">Топ менеджеров</div><div style="color:#6b7280;margin-top:6px;">По количеству закрытых заявок.</div></div>'
            . '<div style="display:flex;gap:8px;flex-wrap:wrap;">%s</div>'
            . '</div>'
            . '<table style="width:100%%;border-collapse:collapse;margin-top:18px;min-width:600px;">'
            . '<thead><tr style="text-align:left;background:#f9fafb;"><th style="padding:12px;">#</th><th style="padding:12px;">Менеджер</th><th style="padding:12px;">Закрыто</th></tr></thead>'
            . '<tbody>%s</tbody>'
            . '</table>'
            . '</div>'
            . '</div>',
            $this->summaryCard('За сегодня', LeadStatistics::closedCount(LeadStatistics::PERIOD_TODAY)),
            $this->summaryCard('За неделю', LeadStatistics::closedCount(LeadStatistics::PERIOD_WEEK)),
            $this->summaryCard('За все время', LeadStatistics::closedCount(LeadStatistics::PERIOD_ALL)),
            $this->periodButtons('closed_period', $closedPeriod, $periods),
            LeadStatistics::closedCount($closedPeriod),
            e($periods[$closedPeriod]),
            $this->periodButtons('manager_period', $managerPeriod, $periods),
            $managerRows === '' ? '<tr><td colspan="3" style="padding:20px;text-align:center;color:#6b7280;">Данных за выбранный период нет.</td></tr>' : $managerRows
        );
    }

    private function summaryCard(string $label, int $value): string
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

    /**
     * @param array<string, string> $periods
     */
    private function periodButtons(string $queryKey, string $activePeriod, array $periods): string
    {
        return collect($periods)
            ->map(function (string $label, string $key) use ($queryKey, $activePeriod): string {
                $query = array_merge(request()->query(), [$queryKey => $key]);
                $href = '?' . http_build_query($query);

                return sprintf(
                    '<a href="%s" style="padding:8px 14px;border-radius:999px;border:1px solid %s;background:%s;color:#111827;text-decoration:none;font-weight:600;">%s</a>',
                    e($href),
                    $key === $activePeriod ? '#1d4ed8' : '#d1d5db',
                    $key === $activePeriod ? '#dbeafe' : '#ffffff',
                    e($label)
                );
            })
            ->implode(' ');
    }

    private function normalizePeriod(string $period): string
    {
        return array_key_exists($period, LeadStatistics::periods()) ? $period : LeadStatistics::PERIOD_TODAY;
    }
}
