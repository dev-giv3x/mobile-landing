<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\Module::truncate();

        $data = [
            [
                'title' => 'Авторизация',
                'content' => 'Безопасный доступ информации',
                'primary_icon' => 'auth-icon',
                'secondary_icon' => 'second-auth-icon',
                'secondary_content' => 'Управление доступом',
                'sort_order' => 1
            ],

            [
                'title' => 'Онбординг сотрудников',
                'content' => 'Быстрая адаптация новичков',
                'primary_icon' => 'on-boarding-icon',
                'secondary_icon' => 'second-on-boarding-icon',
                'secondary_content' => 'Графики и инструктажи',
                'sort_order' => 2
            ],

            [
                'title' => 'Уведомления',
                'content' => 'Важные оповещения',
                'primary_icon' => 'notification-icon',
                'secondary_icon' => 'second-notification-icon',
                'secondary_content' => 'Push-уведомления',
                'sort_order' => 3
            ],

            [
                'title' => 'Вопрос к директору',
                'content' => 'Форма отправки вопросов',
                'primary_icon' => 'question-icon',
                'secondary_icon' => 'second-question-icon',
                'secondary_content' => 'Публичные ответы руководства',
                'sort_order' => 4
            ],

            [
                'title' => 'Барахолка',
                'content' => 'Обьявления сотрудников',
                'primary_icon' => 'shop-icon',
                'secondary_icon' => 'second-shop-icon',
                'secondary_content' => 'Продажи и обмен',
                'sort_order' => 5
            ],

            [
                'title' => 'Новости компании',
                'content' => 'Все новости в одном месте',
                'primary_icon' => 'news-icon',
                'secondary_icon' => 'second-news-icon',
                'secondary_content' => 'Все новости в одном месте',
                'sort_order' => 6
            ],
        ];

        foreach ($data as $item) {
            \App\Models\Module::create($item);
        }
    }
}
