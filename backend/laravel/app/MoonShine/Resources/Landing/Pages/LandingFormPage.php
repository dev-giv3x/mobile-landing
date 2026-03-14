<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Landing\Pages;

use App\MoonShine\Resources\Landing\LandingResource;
use App\Support\LandingTemplate;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Components\Collapse;
use MoonShine\UI\Components\FlexibleRender;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Fields\Color;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Json;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends FormPage<LandingResource>
 */
class LandingFormPage extends FormPage
{
    protected function fields(): iterable
    {
        return [
            Grid::make([
                Column::make([
                    Box::make('Основная информация', [
                        Text::make('Название лендинга', 'title')
                            ->required()
                            ->placeholder('Корпоративный лендинг'),

                        Text::make('Название компании', 'company_name')
                            ->required()
                            ->placeholder('Acme Corp'),

                        Slug::make('Будущий URL (slug)', 'slug')
                            ->from('title')
                            ->unique()
                            ->separator('-'),
                    ]),

                    Collapse::make('Стилизация', [
                        Color::make('Основной цвет', 'settings->primary_color')
                            ->default('#1D65C1'),

                        Image::make('Логотип', 'settings->logo')
                            ->dir('landings/logos')
                            ->removable(),
                    ]),

                    Collapse::make('Hero блок', [
                        Switcher::make('Показывать hero', 'content->hero->enabled')
                            ->default(true),

                        Text::make('Надзаголовок', 'content->hero->eyebrow')
                            ->placeholder('Корпоративное приложение'),

                        Text::make('Заголовок', 'content->hero->title')
                            ->required()
                            ->placeholder('Единое цифровое пространство для вашей компании'),

                        Textarea::make('Подзаголовок', 'content->hero->subtitle')
                            ->placeholder('Соберите коммуникации, сервисы и ключевые процессы в одном удобном мобильном интерфейсе.'),

                        Image::make('Изображение hero', 'content->hero->image')
                            ->dir('landings/hero')
                            ->removable(),

                        Text::make('Alt текст изображения', 'content->hero->image_alt')
                            ->placeholder('Превью приложения'),
                    ]),

                    Collapse::make('Цели приложения', [
                        Text::make('Заголовок секции', 'content->goals->section_title')
                            ->placeholder('Цели приложения'),

                        Json::make('Карточки целей', 'content.goals.items')
                            ->fields([
                                Text::make('Заголовок', 'title')->required(),
                                Image::make('Картинка', 'image')
                                    ->dir('landings/goals')
                                    ->removable(),
                            ])
                            ->vertical()
                            ->creatable(limit: 6)
                            ->removable(),
                    ]),

                    Collapse::make('Функционал (модули)', [
                        Text::make('Заголовок секции', 'content->functionality->section_title')
                            ->placeholder('Функционал приложения'),

                        Textarea::make('Описание секции', 'content->functionality->description')
                            ->placeholder('Набор модулей можно адаптировать под процессы компании и роль сотрудников.'),

                        Json::make('Модули', 'content.modules')
                            ->fields([
                                Text::make('Заголовок', 'title')->required(),
                                Textarea::make('Контент', 'content')->required(),
                                Select::make('Начальная иконка', 'primary_icon')
                                    ->options(LandingTemplate::iconOptions())
                                    ->searchable(),
                                Text::make('Вторичный текст', 'secondary_text')
                                    ->placeholder('Push-уведомления и лента новостей'),
                                Select::make('Вторичная иконка', 'secondary_icon')
                                    ->options(LandingTemplate::iconOptions())
                                    ->searchable(),
                            ])
                            ->vertical()
                            ->creatable(limit: 12)
                            ->removable(),
                    ]),

                    Collapse::make('Структура приложения', [
                        Text::make('Заголовок секции', 'content->structure->section_title')
                            ->placeholder('Структура приложения'),

                        Text::make('Заголовок блока: главный экран', 'content->structure->home_title')
                            ->placeholder('Главный экран'),
                        Textarea::make('Описание: главный экран', 'content->structure->home_description')
                            ->placeholder('Главные новости, быстрые действия, важные уведомления и персональные виджеты сотрудника.'),

                        Text::make('Заголовок блока: сервисы', 'content->structure->services_title')
                            ->placeholder('Страница сервисов'),
                        Textarea::make('Описание: сервисы', 'content->structure->services_description')
                            ->placeholder('Каталог внутренних сервисов, заявок, справок и рабочих сценариев с быстрым доступом.'),

                        Text::make('Заголовок блока: коммуникации', 'content->structure->communications_title')
                            ->placeholder('Раздел коммуникаций'),
                        Textarea::make('Описание: коммуникации', 'content->structure->communications_description')
                            ->placeholder('Лента новостей, объявления, обсуждения и каналы связи между сотрудниками и отделами.'),
                    ]),

                    Collapse::make('Что получает бизнес', [
                        Text::make('Заголовок секции', 'content->advantages->section_title')
                            ->placeholder('Что получает бизнес'),

                        Json::make('Карточки преимуществ', 'content.advantages.items')
                            ->fields([
                                Text::make('Заголовок', 'title')->required(),
                                Image::make('Картинка', 'image')
                                    ->dir('landings/advantages')
                                    ->removable(),
                            ])
                            ->vertical()
                            ->creatable(limit: 6)
                            ->removable(),
                    ]),
                ])->columnSpan(7),

                Column::make([
                    Box::make('Предпросмотр лендинга', [
                        FlexibleRender::make(<<<'HTML'
<div id="landing-preview-root" style="position: sticky; top: 16px;"></div>
<script>
(() => {
    const previewRoot = document.getElementById('landing-preview-root');
    if (!previewRoot || previewRoot.dataset.initialized === '1') {
        return;
    }

    previewRoot.dataset.initialized = '1';

    const form = previewRoot.closest('form');
    if (!form) {
        previewRoot.innerHTML = '<div style="padding:16px;border:1px solid #e5e7eb;border-radius:16px;background:#fff;">Форма не найдена</div>';
        return;
    }

    const defaults = {
        title: 'Корпоративный лендинг',
        company_name: 'Acme Corp',
        slug: 'acme-corp',
        settings: {
            primary_color: '#1D65C1',
            logo: null,
        },
        content: {
            hero: {
                enabled: true,
                eyebrow: 'Корпоративное приложение',
                title: 'Единое цифровое пространство для вашей компании',
                subtitle: 'Соберите коммуникации, сервисы и ключевые процессы в одном удобном мобильном интерфейсе.',
                image: null,
                image_alt: 'Превью приложения',
            },
            goals: {
                section_title: 'Цели приложения',
                items: [
                    { title: 'Собрать ключевые сервисы в одном интерфейсе', image: null },
                    { title: 'Упростить доступ сотрудников к информации', image: null },
                    { title: 'Ускорить внутренние коммуникации и процессы', image: null },
                ],
            },
            functionality: {
                section_title: 'Функционал приложения',
                description: 'Набор модулей можно адаптировать под процессы компании и роль сотрудников.',
            },
            modules: [
                { title: 'Новости компании', content: 'Публикуйте важные обновления и централизуйте коммуникацию.', secondary_text: 'Push-уведомления и лента новостей' },
                { title: 'Онбординг', content: 'Ускорьте адаптацию сотрудников и стандартизируйте обучение.', secondary_text: 'Чек-листы, материалы и инструкции' },
                { title: 'Сервисы для сотрудников', content: 'Соберите заявки, справки и обращения в одном месте.', secondary_text: 'Быстрый доступ к внутренним сервисам' },
            ],
            structure: {
                section_title: 'Структура приложения',
                home_title: 'Главный экран',
                home_description: 'Главные новости, быстрые действия, важные уведомления и персональные виджеты сотрудника.',
                services_title: 'Страница сервисов',
                services_description: 'Каталог внутренних сервисов, заявок, справок и рабочих сценариев с быстрым доступом.',
                communications_title: 'Раздел коммуникаций',
                communications_description: 'Лента новостей, объявления, обсуждения и каналы связи между сотрудниками и отделами.',
            },
            advantages: {
                section_title: 'Что получает бизнес',
                items: [
                    { title: 'Рост эффективности', image: null },
                    { title: 'Контроль и безопасность', image: null },
                    { title: 'Улучшение коммуникации', image: null },
                ],
            },
        },
    };

    const objectUrls = new Map();

    const escapeHtml = (value) => String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');

    const normalizeAsset = (value) => {
        if (!value) {
            return '';
        }

        if (typeof value === 'object' && value.__fileUrl) {
            return value.__fileUrl;
        }

        if (typeof value !== 'string') {
            return '';
        }

        if (value.startsWith('http://') || value.startsWith('https://') || value.startsWith('/')) {
            return value;
        }

        return `/storage/${value.replace(/^storage\//, '')}`;
    };

    const parsePath = (key) => {
        const normalized = key
            .replaceAll('->', '.')
            .replaceAll('][', '.')
            .replaceAll('[', '.')
            .replaceAll(']', '')
            .replaceAll('__rm__', '')
            .replace(/^\./, '');

        return normalized.split('.').filter(Boolean).map((segment) => {
            if (segment.endsWith('_hidden')) {
                return segment.slice(0, -7);
            }

            return segment;
        });
    };

    const setNestedValue = (target, path, value) => {
        let cursor = target;

        path.forEach((segment, index) => {
            const isLast = index === path.length - 1;
            const nextSegment = path[index + 1];
            const nextIsIndex = /^\d+$/.test(nextSegment || '');

            if (isLast) {
                cursor[segment] = value;
                return;
            }

            if (!(segment in cursor) || typeof cursor[segment] !== 'object' || cursor[segment] === null) {
                cursor[segment] = nextIsIndex ? [] : {};
            }

            cursor = cursor[segment];
        });
    };

    const normalizeArrays = (value) => {
        if (Array.isArray(value)) {
            return value
                .map((item) => normalizeArrays(item))
                .filter((item) => item !== null && item !== undefined && !(typeof item === 'string' && item === ''));
        }

        if (value && typeof value === 'object') {
            const keys = Object.keys(value);
            const allNumeric = keys.length > 0 && keys.every((key) => /^\d+$/.test(key));

            if (allNumeric) {
                return keys
                    .sort((a, b) => Number(a) - Number(b))
                    .map((key) => normalizeArrays(value[key]))
                    .filter((item) => item !== null && item !== undefined && !(typeof item === 'string' && item === ''));
            }

            return Object.fromEntries(keys.map((key) => [key, normalizeArrays(value[key])])) ;
        }

        return value;
    };

    const mergeDeep = (base, source) => {
        if (Array.isArray(base)) {
            return Array.isArray(source) && source.length ? source : base;
        }

        if (base && typeof base === 'object') {
            const result = { ...base };
            const sourceObject = source && typeof source === 'object' ? source : {};

            Object.keys(sourceObject).forEach((key) => {
                result[key] = key in result ? mergeDeep(result[key], sourceObject[key]) : sourceObject[key];
            });

            return result;
        }

        return source === undefined || source === null || source === '' ? base : source;
    };

    const readFormState = () => {
        const formData = new FormData(form);
        const state = {};

        formData.forEach((value, key) => {
            if (key.startsWith('_token') || key.startsWith('_method') || key.includes('__table')) {
                return;
            }

            if (value instanceof File) {
                if (!value.name) {
                    return;
                }

                if (objectUrls.has(key)) {
                    URL.revokeObjectURL(objectUrls.get(key));
                }

                const fileUrl = URL.createObjectURL(value);
                objectUrls.set(key, fileUrl);
                setNestedValue(state, parsePath(key), { __fileUrl: fileUrl, name: value.name });
                return;
            }

            setNestedValue(state, parsePath(key), value);
        });

        const merged = mergeDeep(defaults, normalizeArrays(state));
        merged.content.hero.enabled = !!Number(merged.content.hero.enabled ?? 0) || merged.content.hero.enabled === true;

        return merged;
    };

    const renderImage = (src, alt, extraStyle = '') => {
        const normalized = normalizeAsset(src);
        if (!normalized) {
            return '';
        }

        return `<img src="${escapeHtml(normalized)}" alt="${escapeHtml(alt || '')}" style="${extraStyle}" />`;
    };

    const renderListCards = (items, cardRenderer, columns = 1) => {
        const safeItems = Array.isArray(items) ? items.filter((item) => item && (item.title || item.content || item.secondary_text)) : [];
        if (!safeItems.length) {
            return '';
        }

        return `<div style="display:grid;grid-template-columns:repeat(${columns}, minmax(0, 1fr));gap:12px;">${safeItems.map(cardRenderer).join('')}</div>`;
    };

    const structureCards = (structure, primaryColor) => {
        const items = [
            { title: structure.home_title, description: structure.home_description },
            { title: structure.services_title, description: structure.services_description },
            { title: structure.communications_title, description: structure.communications_description },
        ].filter((item) => item.title || item.description);

        return renderListCards(items, (item) => `
            <div style="padding:18px;border-radius:18px;background:#fff;border:1px solid #e5e7eb;">
                <div style="display:inline-flex;padding:6px 10px;border-radius:999px;background:${escapeHtml(primaryColor)}12;color:${escapeHtml(primaryColor)};font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;">Р­РєСЂР°РЅ</div>
                <div style="margin-top:12px;font-size:16px;font-weight:700;line-height:1.3;color:#10203d;">${escapeHtml(item.title || '')}</div>
                <div style="margin-top:10px;font-size:14px;line-height:1.6;color:#536277;">${escapeHtml(item.description || '')}</div>
            </div>
        `, 1);
    };

    const renderPreview = () => {

        const data = readFormState();
        const primaryColor = data.settings?.primary_color || '#1D65C1';
        const hero = data.content?.hero || {};
        const goals = data.content?.goals || {};
        const functionality = data.content?.functionality || {};
        const modules = Array.isArray(data.content?.modules) ? data.content.modules : [];
        const structure = data.content?.structure || {};
        const advantages = data.content?.advantages || {};

        previewRoot.innerHTML = `
            <div style="border:1px solid #dbe4f0;border-radius:28px;padding:18px;background:linear-gradient(180deg, #ffffff 0%, #f6f9fc 100%);box-shadow:0 24px 80px rgba(15, 23, 42, 0.08);font-family:Inter, Arial, sans-serif;color:#11203a;">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:20px;">
                    <div>
                        <div style="font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#6b7a90;">Live preview</div>
                        <div style="font-size:22px;font-weight:700;line-height:1.2;">${escapeHtml(data.title || data.company_name)}</div>
                        <div style="font-size:13px;color:#6b7a90;margin-top:4px;">/${escapeHtml(data.slug || '')}</div>
                    </div>
                    <div>
                        ${renderImage(data.settings?.logo, data.company_name, 'width:48px;height:48px;object-fit:contain;border-radius:12px;background:#fff;border:1px solid #e5e7eb;padding:6px;')}
                    </div>
                </div>

                ${hero.enabled ? `
                <section style="padding:22px;border-radius:24px;background:${escapeHtml(primaryColor)}12;border:1px solid ${escapeHtml(primaryColor)}33;display:grid;grid-template-columns:minmax(0,1.1fr) minmax(120px,0.9fr);gap:18px;align-items:center;">
                    <div>
                        <div style="font-size:12px;text-transform:uppercase;letter-spacing:0.08em;color:${escapeHtml(primaryColor)};font-weight:700;">${escapeHtml(hero.eyebrow || '')}</div>
                        <h2 style="margin:10px 0 0;font-size:28px;line-height:1.12;font-weight:800;color:#10203d;">${escapeHtml(hero.title || '')}</h2>
                        <p style="margin:12px 0 0;font-size:14px;line-height:1.6;color:#536277;">${escapeHtml(hero.subtitle || '')}</p>
                    </div>
                    <div style="display:flex;justify-content:center;align-items:center;min-height:160px;">
                        ${renderImage(hero.image, hero.image_alt, 'width:100%;max-width:220px;max-height:220px;object-fit:contain;') || `<div style="width:100%;max-width:220px;aspect-ratio:1/1;border-radius:20px;background:linear-gradient(135deg, ${escapeHtml(primaryColor)}22 0%, #ffffff 100%);border:1px dashed ${escapeHtml(primaryColor)}55;"></div>`}
                    </div>
                </section>` : ''}

                <section style="margin-top:20px;">
                    <div style="font-size:22px;font-weight:700;text-align:center;">${escapeHtml(goals.section_title || '')}</div>
                    <div style="margin-top:14px;">${renderListCards(goals.items, (item) => `<div style="padding:16px;border-radius:18px;background:#fff;border:1px solid #e5e7eb;display:flex;align-items:center;gap:12px;min-height:84px;">${renderImage(item.hidden_image, item.title, 'width:48px;height:48px;object-fit:contain;') || `<div style="width:48px;height:48px;border-radius:14px;background:${escapeHtml(primaryColor)}12;"></div>`}<div style="font-size:14px;font-weight:600;line-height:1.4;">${escapeHtml(item.title || '')}</div></div>`, 1)}</div>
                </section>

                <section style="margin-top:22px;">
                    <div style="font-size:22px;font-weight:700;text-align:center;">${escapeHtml(functionality.section_title || '')}</div>
                    <p style="margin:10px auto 0;max-width:540px;text-align:center;font-size:14px;line-height:1.6;color:#536277;">${escapeHtml(functionality.description || '')}</p>
                    <div style="margin-top:16px;">${renderListCards(modules, (item) => `<div style="padding:16px;border-radius:20px;background:#fff;border:1px solid #e5e7eb;box-shadow:0 8px 30px rgba(15, 23, 42, 0.05);"><div style="display:flex;align-items:center;justify-content:space-between;gap:12px;"><div style="font-size:16px;font-weight:700;line-height:1.3;">${escapeHtml(item.title || '')}</div><div style="width:36px;height:36px;border-radius:12px;background:${escapeHtml(primaryColor)}18;color:${escapeHtml(primaryColor)};display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;">ICON</div></div><div style="height:1px;background:#edf2f7;margin:12px 0;"></div><div style="font-size:14px;line-height:1.55;color:#536277;">${escapeHtml(item.content || '')}</div><div style="height:1px;background:#edf2f7;margin:12px 0;"></div><div style="font-size:13px;line-height:1.5;color:#536277;display:flex;align-items:center;gap:8px;"><span style="width:20px;height:20px;border-radius:999px;background:${escapeHtml(primaryColor)}12;display:inline-block;"></span>${escapeHtml(item.secondary_text || '')}</div></div>`, 1)}</div>
                </section>

                <section style="margin-top:22px;">
                    <div style="font-size:22px;font-weight:700;text-align:center;">${escapeHtml(structure.section_title || '')}</div>
                    <div style="margin-top:14px;">${structureCards(structure, primaryColor)}</div>
                </section>

                <section style="margin-top:22px;">
                    <div style="font-size:22px;font-weight:700;text-align:center;">${escapeHtml(advantages.section_title || '')}</div>
                    <div style="margin-top:14px;">${renderListCards(advantages.items, (item) => `<div style="padding:18px;border-radius:18px;background:#fff;border:1px solid #e5e7eb;text-align:center;">${renderImage(item.hidden_image, item.title, 'width:72px;height:72px;object-fit:contain;margin:0 auto 12px;') || `<div style="width:72px;height:72px;border-radius:20px;background:${escapeHtml(primaryColor)}12;margin:0 auto 12px;"></div>`}<div style="font-size:14px;font-weight:600;line-height:1.4;">${escapeHtml(item.title || '')}</div></div>`, 3)}</div>
                </section>
            </div>
        `;
    };

    const debounce = (func, wait) => {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => func(...args), wait);
        };
    };

    const debouncedRender = debounce(renderPreview, 120);

    form.addEventListener('input', debouncedRender);
    form.addEventListener('change', debouncedRender);
    form.addEventListener('click', () => setTimeout(renderPreview, 30));

    const observer = new MutationObserver((mutations) => {
        const isInsidePreview = mutations.every((mutation) => previewRoot.contains(mutation.target));
        if (!isInsidePreview) {
            debouncedRender();
        }
    });

    observer.observe(form, {
        childList: true,
        subtree: true,
        attributes: true,
    });

    renderPreview();
})();
</script>
HTML),
                    ]),
                ])->columnSpan(5),
            ]),
        ];
    }
}

