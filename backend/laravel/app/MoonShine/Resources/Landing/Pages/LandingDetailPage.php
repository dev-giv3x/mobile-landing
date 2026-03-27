<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Landing\Pages;

use App\Models\Landing;
use App\MoonShine\Resources\Landing\LandingResource;
use App\Support\LandingTemplate;
use Illuminate\Support\Facades\File;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\FlexibleRender;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\ID;
use Throwable;

/**
 * @extends DetailPage<LandingResource>
 */
class LandingDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    protected function modifyDetailComponent(ComponentContract $component): ComponentContract
    {
        return $component;
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            ...parent::topLayer(),
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            Box::make('Предпросмотр лендинга', [
                FlexibleRender::make(fn (): string => $this->renderPreview()),
            ]),
            ...parent::mainLayer(),
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer(),
        ];
    }

    private function renderPreview(): string
    {
        $landing = $this->getItem();

        if (! $landing instanceof Landing) {
            return '<div style="padding:18px;border-radius:18px;background:#fff;border:1px solid #e5e7eb;">Лендинг не найден.</div>';
        }

        $data = LandingTemplate::normalize($landing);
        $primaryColor = (string) ($data['settings']['primary_color'] ?? '#1D65C1');
        $hero = is_array($data['content']['hero'] ?? null) ? $data['content']['hero'] : [];
        $goals = is_array($data['content']['goals'] ?? null) ? $data['content']['goals'] : [];
        $functionality = is_array($data['content']['functionality'] ?? null) ? $data['content']['functionality'] : [];
        $modules = is_array($data['content']['modules'] ?? null) ? $data['content']['modules'] : [];
        $structure = is_array($data['content']['structure'] ?? null) ? $data['content']['structure'] : [];
        $advantages = is_array($data['content']['advantages'] ?? null) ? $data['content']['advantages'] : [];

        return sprintf(
            '<div style="max-width:980px;margin:0 auto;border:1px solid #dbe4f0;border-radius:24px;padding:16px;background:linear-gradient(180deg, #ffffff 0%%, #f6f9fc 100%%);box-shadow:0 18px 56px rgba(15, 23, 42, 0.08);font-family:Inter, Arial, sans-serif;color:#11203a;">'
            . '<div style="display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:20px;">'
            . '<div><div style="font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#6b7a90;">Landing preview</div><div style="font-size:22px;font-weight:700;line-height:1.2;">%s</div><div style="font-size:13px;color:#6b7a90;margin-top:4px;">/%s</div></div>'
            . '<div>%s</div>'
            . '</div>'
            . '%s'
            . '<section style="margin-top:20px;"><div style="font-size:22px;font-weight:700;text-align:center;">%s</div><div style="margin-top:14px;">%s</div></section>'
            . '<section style="margin-top:22px;"><div style="font-size:22px;font-weight:700;text-align:center;">%s</div><p style="margin:10px auto 0;max-width:540px;text-align:center;font-size:14px;line-height:1.6;color:#536277;">%s</p><div style="margin-top:16px;">%s</div></section>'
            . '<section style="margin-top:22px;"><div style="font-size:22px;font-weight:700;text-align:center;">%s</div><div style="margin-top:14px;">%s</div></section>'
            . '<section style="margin-top:22px;"><div style="font-size:22px;font-weight:700;text-align:center;">%s</div><div style="margin-top:14px;">%s</div></section>'
            . '</div>',
            e((string) ($data['title'] ?? $data['company_name'] ?? '')),
            e((string) ($data['slug'] ?? '')),
            $this->renderImage(
                $data['settings']['logo'] ?? null,
                (string) ($data['company_name'] ?? ''),
                'width:48px;height:48px;object-fit:contain;border-radius:12px;background:#fff;border:1px solid #e5e7eb;padding:6px;'
            ),
            $this->renderHero($hero, $primaryColor),
            e((string) ($goals['section_title'] ?? '')),
            $this->renderGoalCards($goals['items'] ?? [], $primaryColor),
            e((string) ($functionality['section_title'] ?? '')),
            e((string) ($functionality['description'] ?? '')),
            $this->renderModuleCards($modules, $primaryColor),
            e((string) ($structure['section_title'] ?? '')),
            $this->renderStructureCards($structure, $primaryColor),
            e((string) ($advantages['section_title'] ?? '')),
            $this->renderAdvantageCards($advantages['items'] ?? [], $primaryColor)
        );
    }

    /**
     * @param array<string, mixed> $hero
     */
    private function renderHero(array $hero, string $primaryColor): string
    {
        $enabled = (bool) ($hero['enabled'] ?? false);

        if (! $enabled) {
            return '';
        }

        $image = $this->renderImage(
            $hero['image'] ?? null,
            (string) ($hero['image_alt'] ?? ''),
            'width:100%;max-width:220px;max-height:220px;object-fit:contain;'
        );

        if ($image === '') {
            $image = sprintf(
                '<div style="width:100%%;max-width:220px;aspect-ratio:1/1;border-radius:20px;background:linear-gradient(135deg, %s22 0%%, #ffffff 100%%);border:1px dashed %s55;"></div>',
                e($primaryColor),
                e($primaryColor)
            );
        }

        return sprintf(
            '<section style="padding:18px;border-radius:22px;background:%s12;border:1px solid %s33;display:grid;grid-template-columns:minmax(0,1.05fr) minmax(120px,0.8fr);gap:16px;align-items:center;">'
            . '<div><div style="font-size:11px;text-transform:uppercase;letter-spacing:0.08em;color:%s;font-weight:700;">%s</div><h2 style="margin:10px 0 0;font-size:24px;line-height:1.12;font-weight:800;color:#10203d;">%s</h2><p style="margin:10px 0 0;font-size:13px;line-height:1.55;color:#536277;">%s</p></div>'
            . '<div style="display:flex;justify-content:center;align-items:center;min-height:140px;">%s</div>'
            . '</section>',
            e($primaryColor),
            e($primaryColor),
            e($primaryColor),
            e((string) ($hero['eyebrow'] ?? '')),
            e((string) ($hero['title'] ?? '')),
            e((string) ($hero['subtitle'] ?? '')),
            $image
        );
    }

    /**
     * @param iterable<mixed> $items
     */
    private function renderGoalCards(iterable $items, string $primaryColor): string
    {
        return $this->renderCards(
            $items,
            function (array $item) use ($primaryColor): string {
                $image = $this->renderImage(
                    $item['image'] ?? null,
                    (string) ($item['title'] ?? ''),
                    'width:48px;height:48px;object-fit:contain;'
                );

                if ($image === '') {
                    $image = sprintf(
                        '<div style="width:48px;height:48px;border-radius:14px;background:%s12;"></div>',
                        e($primaryColor)
                    );
                }

                return sprintf(
                    '<div style="padding:16px;border-radius:18px;background:#fff;border:1px solid #e5e7eb;display:flex;align-items:center;gap:12px;min-height:84px;">%s<div style="font-size:14px;font-weight:600;line-height:1.4;">%s</div></div>',
                    $image,
                    e((string) ($item['title'] ?? ''))
                );
            }
        );
    }

    /**
     * @param iterable<mixed> $items
     */
    private function renderModuleCards(iterable $items, string $primaryColor): string
    {
        return $this->renderCards(
            $items,
            function (array $item) use ($primaryColor): string {
                $primaryIcon = $this->renderIcon((string) ($item['primary_icon'] ?? ''), 20, $primaryColor);
                $secondaryIcon = $this->renderIcon((string) ($item['secondary_icon'] ?? ''), 12, $primaryColor);

                if ($primaryIcon === '') {
                    $primaryIcon = sprintf(
                        '<div style="width:16px;height:16px;border-radius:6px;background:%s55;"></div>',
                        e($primaryColor)
                    );
                }

                return sprintf(
                    '<div style="padding:16px;border-radius:20px;background:#fff;border:1px solid #e5e7eb;box-shadow:0 8px 30px rgba(15, 23, 42, 0.05);">'
                    . '<div style="display:flex;align-items:center;justify-content:space-between;gap:12px;"><div style="font-size:16px;font-weight:700;line-height:1.3;">%s</div><div style="width:36px;height:36px;border-radius:12px;background:%s18;display:flex;align-items:center;justify-content:center;">%s</div></div>'
                    . '<div style="height:1px;background:#edf2f7;margin:12px 0;"></div>'
                    . '<div style="font-size:14px;line-height:1.55;color:#536277;">%s</div>'
                    . '<div style="height:1px;background:#edf2f7;margin:12px 0;"></div>'
                    . '<div style="font-size:13px;line-height:1.5;color:#536277;display:flex;align-items:center;gap:8px;"><span style="width:20px;height:20px;border-radius:999px;background:%s12;display:flex;align-items:center;justify-content:center;">%s</span>%s</div>'
                    . '</div>',
                    e((string) ($item['title'] ?? '')),
                    e($primaryColor),
                    $primaryIcon,
                    e((string) ($item['content'] ?? '')),
                    e($primaryColor),
                    $secondaryIcon,
                    e((string) ($item['secondary_text'] ?? ''))
                );
            }
        );
    }

    /**
     * @param array<string, mixed> $structure
     */
    private function renderStructureCards(array $structure, string $primaryColor): string
    {
        $items = [
            [
                'title' => $structure['home_title'] ?? null,
                'description' => $structure['home_description'] ?? null,
            ],
            [
                'title' => $structure['services_title'] ?? null,
                'description' => $structure['services_description'] ?? null,
            ],
            [
                'title' => $structure['communications_title'] ?? null,
                'description' => $structure['communications_description'] ?? null,
            ],
        ];

        return $this->renderCards(
            $items,
            static function (array $item) use ($primaryColor): string {
                return sprintf(
                    '<div style="padding:18px;border-radius:18px;background:#fff;border:1px solid #e5e7eb;">'
                    . '<div style="display:inline-flex;padding:6px 10px;border-radius:999px;background:%s12;color:%s;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;">Модуль</div>'
                    . '<div style="margin-top:12px;font-size:16px;font-weight:700;line-height:1.3;color:#10203d;">%s</div>'
                    . '<div style="margin-top:10px;font-size:14px;line-height:1.6;color:#536277;">%s</div>'
                    . '</div>',
                    e($primaryColor),
                    e($primaryColor),
                    e((string) ($item['title'] ?? '')),
                    e((string) ($item['description'] ?? ''))
                );
            }
        );
    }

    /**
     * @param iterable<mixed> $items
     */
    private function renderAdvantageCards(iterable $items, string $primaryColor): string
    {
        return $this->renderCards(
            $items,
            function (array $item) use ($primaryColor): string {
                $image = $this->renderImage(
                    $item['image'] ?? null,
                    (string) ($item['title'] ?? ''),
                    'width:72px;height:72px;object-fit:contain;margin:0 auto 12px;'
                );

                if ($image === '') {
                    $image = sprintf(
                        '<div style="width:72px;height:72px;border-radius:20px;background:%s12;margin:0 auto 12px;"></div>',
                        e($primaryColor)
                    );
                }

                return sprintf(
                    '<div style="padding:18px;border-radius:18px;background:#fff;border:1px solid #e5e7eb;text-align:center;">%s<div style="font-size:14px;font-weight:600;line-height:1.4;">%s</div></div>',
                    $image,
                    e((string) ($item['title'] ?? ''))
                );
            },
            3
        );
    }

    /**
     * @param iterable<mixed> $items
     * @param callable(array<string, mixed>): string $renderer
     */
    private function renderCards(iterable $items, callable $renderer, int $columns = 1): string
    {
        $cards = [];

        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            if (
                ($item['title'] ?? null) === null
                && ($item['content'] ?? null) === null
                && ($item['description'] ?? null) === null
                && ($item['secondary_text'] ?? null) === null
            ) {
                continue;
            }

            $cards[] = $renderer($item);
        }

        if ($cards === []) {
            return '';
        }

        return sprintf(
            '<div style="display:grid;grid-template-columns:repeat(%d, minmax(0, 1fr));gap:12px;">%s</div>',
            $columns,
            implode('', $cards)
        );
    }

    private function renderImage(mixed $source, string $alt, string $style = ''): string
    {
        if (! is_string($source) || $source === '') {
            return '';
        }

        return sprintf(
            '<img src="%s" alt="%s" style="%s">',
            e($source),
            e($alt),
            e($style)
        );
    }

    private function renderIcon(string $name, int $size, string $color): string
    {
        $icon = $this->resolveIconMarkup($name);

        if ($icon === '') {
            return '';
        }

        return sprintf(
            '<div style="width:%dpx;height:%dpx;color:%s;display:flex;align-items:center;justify-content:center;">%s</div>',
            $size,
            $size,
            e($color),
            $icon
        );
    }

    private function resolveIconMarkup(string $name): string
    {
        if ($name === '') {
            return '';
        }

        $icons = $this->previewIcons();
        $normalized = str_ends_with($name, '-mask') ? $name : $name . '-mask';
        $icon = $icons[$normalized] ?? $icons[$name] ?? '';

        if ($icon === '') {
            return '';
        }

        return str_replace('<svg ', '<svg style="width:100%;height:100%;display:block;" ', $icon);
    }

    /**
     * @return array<string, string>
     */
    private function previewIcons(): array
    {
        static $icons;

        if (is_array($icons)) {
            return $icons;
        }

        $icons = [];
        $directory = base_path('../../frontend/vue/src/assets/icons');

        if (! is_dir($directory)) {
            return $icons;
        }

        foreach (File::files($directory) as $file) {
            if ($file->getExtension() !== 'svg') {
                continue;
            }

            $icons[$file->getBasename('.svg')] = $file->getContents();
        }

        return $icons;
    }
}
