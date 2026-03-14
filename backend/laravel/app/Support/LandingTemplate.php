<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Landing;
use Illuminate\Support\Facades\Storage;

final class LandingTemplate
{
    /**
     * @return array<string, string>
     */
    public static function iconOptions(): array
    {
        return [
            'auth-icon' => 'Авторизация',
            'news-icon' => 'Новости',
            'notification-icon' => 'Уведомления',
            'on-boarding-icon' => 'Онбординг',
            'question-icon' => 'Вопросы',
            'shop-icon' => 'Магазин',
            'second-auth-icon' => 'Авторизация 2',
            'second-news-icon' => 'Новости 2',
            'second-notification-icon' => 'Уведомления 2',
            'second-on-boarding-icon' => 'Онбординг 2',
            'second-question-icon' => 'Вопросы 2',
            'second-shop-icon' => 'Магазин 2',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        return [
            'title' => 'Корпоративный лендинг',
            'company_name' => 'Acme Corp',
            'slug' => 'acme-corp',
            'settings' => [
                'primary_color' => '#1D65C1',
                'logo' => null,
            ],
            'content' => [
                'hero' => [
                    'enabled' => true,
                    'eyebrow' => 'Корпоративное приложение',
                    'title' => 'Единое цифровое пространство для вашей компании',
                    'subtitle' => 'Соберите коммуникации, сервисы и ключевые процессы в одном удобном мобильном интерфейсе.',
                    'image' => null,
                    'image_alt' => 'Превью приложения',
                ],
                'goals' => [
                    'section_title' => 'Цели приложения',
                    'items' => [
                        ['title' => 'Собрать ключевые сервисы в одном интерфейсе', 'image' => null],
                        ['title' => 'Упростить доступ сотрудников к информации', 'image' => null],
                        ['title' => 'Ускорить внутренние коммуникации и процессы', 'image' => null],
                    ],
                ],
                'functionality' => [
                    'section_title' => 'Функционал приложения',
                    'description' => 'Набор модулей можно адаптировать под процессы компании и роль сотрудников.',
                ],
                'modules' => [
                    [
                        'title' => 'Новости компании',
                        'content' => 'Публикуйте важные обновления и централизуйте коммуникацию.',
                        'primary_icon' => 'news-icon',
                        'secondary_text' => 'Push-уведомления и лента новостей',
                        'secondary_icon' => 'second-news-icon',
                    ],
                    [
                        'title' => 'Онбординг',
                        'content' => 'Ускорьте адаптацию сотрудников и стандартизируйте обучение.',
                        'primary_icon' => 'on-boarding-icon',
                        'secondary_text' => 'Чек-листы, материалы и инструкции',
                        'secondary_icon' => 'second-on-boarding-icon',
                    ],
                    [
                        'title' => 'Сервисы для сотрудников',
                        'content' => 'Соберите заявки, справки и обращения в одном месте.',
                        'primary_icon' => 'shop-icon',
                        'secondary_text' => 'Быстрый доступ к внутренним сервисам',
                        'secondary_icon' => 'second-shop-icon',
                    ],
                ],
                'structure' => [
                    'section_title' => 'Структура приложения',
                    'home_title' => 'Главный экран',
                    'home_description' => 'Главные новости, быстрые действия, важные уведомления и персональные виджеты сотрудника.',
                    'services_title' => 'Страница сервисов',
                    'services_description' => 'Каталог внутренних сервисов, заявок, справок и рабочих сценариев с быстрым доступом.',
                    'communications_title' => 'Раздел коммуникаций',
                    'communications_description' => 'Лента новостей, объявления, обсуждения и каналы связи между сотрудниками и отделами.',
                ],
                'advantages' => [
                    'section_title' => 'Что получает бизнес',
                    'items' => [
                        ['title' => 'Рост эффективности', 'image' => null],
                        ['title' => 'Контроль и безопасность', 'image' => null],
                        ['title' => 'Улучшение коммуникации', 'image' => null],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function normalize(?Landing $landing): array
    {
        $defaults = self::defaults();

        if ($landing === null) {
            return $defaults;
        }

        return self::mergeRecursive($defaults, [
            'title' => $landing->title,
            'company_name' => $landing->company_name,
            'slug' => $landing->slug,
            'settings' => self::normalizeSettings($landing->settings ?? []),
            'content' => self::normalizeContent($landing),
        ]);
    }

    /**
     * @param array<string, mixed> $settings
     * @return array<string, mixed>
     */
    public static function normalizeSettings(array $settings): array
    {
        return [
            'primary_color' => $settings['primary_color'] ?? null,
            'logo' => self::normalizeAssetPaths($settings['logo'] ?? null),
        ];
    }

    /**
     * @param array<string, mixed> $content
     * @return array<string, mixed>
     */
    public static function normalizeContentForForm(Landing $landing, array $content): array
    {
        return self::mergeRecursive(self::defaults()['content'], self::normalizeContentPayload($landing, $content));
    }

    /**
     * @return array<string, mixed>
     */
    private static function normalizeContent(Landing $landing): array
    {
        $content = is_array($landing->content) ? $landing->content : [];

        return self::normalizeContentPayload($landing, $content);
    }

    /**
     * @param array<string, mixed> $content
     * @return array<string, mixed>
     */
    private static function normalizeContentPayload(Landing $landing, array $content): array
    {
        $legacyGoals = $content['problems']['items'] ?? $content['goals']['items'] ?? [];
        $legacyGoalsTitle = $content['goals']['section_title'] ?? $content['problems']['section_title'] ?? null;
        $legacyFunctionality = $content['functionality'] ?? $content['solution'] ?? [];
        $legacyAdvantages = $content['advantages']['items'] ?? [];
        $hero = is_array($content['hero'] ?? null) ? $content['hero'] : [];
        $structure = is_array($content['structure'] ?? null) ? $content['structure'] : [];

        return [
            'hero' => [
                'enabled' => self::normalizeBool($hero['enabled'] ?? $content['hero_enabled'] ?? true),
                'eyebrow' => $hero['eyebrow'] ?? $content['hero_eyebrow'] ?? null,
                'title' => $hero['title'] ?? $content['hero_title'] ?? $landing->title,
                'subtitle' => $hero['subtitle'] ?? $content['hero_subtitle'] ?? null,
                'image' => self::normalizeAssetPaths($hero['image'] ?? $content['hero_image'] ?? null),
                'image_alt' => $hero['image_alt'] ?? $content['hero_image_alt'] ?? $landing->title,
            ],
            'goals' => [
                'section_title' => $legacyGoalsTitle,
                'items' => self::normalizeCards($legacyGoals),
            ],
            'functionality' => [
                'section_title' => $legacyFunctionality['section_title'] ?? null,
                'description' => $legacyFunctionality['description'] ?? null,
            ],
            'modules' => self::normalizeModules($content['modules'] ?? []),
            'structure' => [
                'section_title' => $structure['section_title'] ?? null,
                'home_title' => $structure['home_title'] ?? null,
                'home_description' => $structure['home_description'] ?? null,
                'services_title' => $structure['services_title'] ?? null,
                'services_description' => $structure['services_description'] ?? null,
                'communications_title' => $structure['communications_title'] ?? null,
                'communications_description' => $structure['communications_description'] ?? null,
            ],
            'advantages' => [
                'section_title' => $content['advantages']['section_title'] ?? null,
                'items' => self::normalizeCards($legacyAdvantages),
            ],
        ];
    }

    /**
     * @param iterable<mixed> $items
     * @return array<int, array<string, mixed>>
     */
    private static function normalizeCards(iterable $items): array
    {
        $normalized = [];

        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $normalized[] = [
                'title' => $item['title'] ?? null,
                'image' => self::normalizeAssetPaths($item['image'] ?? null),
            ];
        }

        return $normalized;
    }

    /**
     * @param iterable<mixed> $items
     * @return array<int, array<string, mixed>>
     */
    private static function normalizeModules(iterable $items): array
    {
        $normalized = [];

        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $normalized[] = [
                'title' => $item['title'] ?? null,
                'content' => $item['content'] ?? $item['description'] ?? null,
                'primary_icon' => $item['primary_icon'] ?? $item['icon'] ?? null,
                'secondary_text' => $item['secondary_text'] ?? $item['secondary_content'] ?? null,
                'secondary_icon' => $item['secondary_icon'] ?? null,
            ];
        }

        return $normalized;
    }

    private static function normalizeBool(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (bool) $value;
        }

        return in_array($value, ['true', 'on', 'yes'], true);
    }

    /**
     * @param array<string, mixed> $default
     * @param array<string, mixed> $override
     * @return array<string, mixed>
     */
    private static function mergeRecursive(array $default, array $override): array
    {
        foreach ($override as $key => $value) {
            if (! array_key_exists($key, $default)) {
                $default[$key] = $value;
                continue;
            }

            if (is_array($default[$key]) && is_array($value)) {
                if (array_is_list($default[$key]) || array_is_list($value)) {
                    $default[$key] = $value === [] ? $default[$key] : $value;
                    continue;
                }

                $default[$key] = self::mergeRecursive($default[$key], $value);
                continue;
            }

            if ($value !== null && $value !== '') {
                $default[$key] = $value;
            }
        }

        return $default;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private static function normalizeAssetPaths(mixed $value): mixed
    {
        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $value[$key] = self::normalizeAssetPaths($item);
            }

            return $value;
        }

        if (! is_string($value) || $value === '') {
            return $value;
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        if (str_starts_with($value, 'http://admin.localhost') || str_starts_with($value, 'https://admin.localhost')) {
            return $value;
        }

        if (str_starts_with($value, 'admin.localhost')) {
            return 'http://' . $value;
        }

        if (str_starts_with($value, '/')) {
            return 'http://admin.localhost' . $value;
        }

        $normalized = Storage::url($value);

        if (str_starts_with($normalized, 'http://') || str_starts_with($normalized, 'https://')) {
            return $normalized;
        }

        if (str_starts_with($normalized, '/')) {
            return 'http://admin.localhost' . $normalized;
        }

        return 'http://admin.localhost/storage/' . ltrim($normalized, '/');
    }
}
