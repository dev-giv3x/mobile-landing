# mobile-landing
Дипломный проект / ТЭК
# Инструкция к запуску
Запуск проекта осуществляется через docker при помощи docker-compose.yml docker-compose up -d --build

При первом запуске требуется создать .env в backend/laravel/  Пример .env находится в .env.example

Также при запуске необходимо выполнить docker compose exec php composer install - установка композера для работы бекенда(laravel)

А также необходимо выполнить docker compose exec php php artisan migrate для создания миграций базы данных.

После необходимо выполнить docker compose exec php php artisan migrate --seed для заполнения базы данных тестовой информацией из сидера.

При редактировании клиентской части (frontend'a) использовать docker-compose up -d --build nginx чтобы изменнеия применились.
