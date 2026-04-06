# Справочник музыкальных альбомов

Приложение на Laravel 13 для управления коллекцией музыкальных альбомов.

## Возможности

- **Список альбомов** — карточки с обложкой, названием и исполнителем
- **Пагинация** — 9 альбомов на странице
- **Поиск** — по названию или исполнителю
- **Создание / редактирование / удаление** — доступно авторизованным пользователям
- **Автозаполнение из Last.fm** — по названию альбома подтягиваются исполнитель, описание и обложка
- **Логирование изменений** — все действия записываются в `album_logs`
- **Аутентификация** — регистрация и вход

## Стек

- **Laravel 13** — PHP фреймворк
- **PostgreSQL 17** — база данных
- **Docker** — nginx + php-fpm + postgres
- **Tailwind CSS 4** — стили
- **Vite** — сборка ассетов
- **Last.fm API** — автозаполнение данных

## Требования

- Docker и Docker Compose
- Node.js (для сборки ассетов)

## Установка

### 1. Клонировать репозиторий

```bash
git clone github.com/xGerfy/music-albums-app-laravel
cd "laravel test"
```

### 2. Запустить Docker

```bash
cd docker
docker-compose up -d
```

### 3. Установить зависимости

```bash
docker exec nutnet-php composer install
```

### 4. Миграции и сидеры

```bash
docker exec nutnet-php php artisan migrate
docker exec nutnet-php php artisan db:seed
```

### 5. Собрать ассеты

```bash
npm install
npm run build
```

### 6. Открыть приложение

http://localhost

## Тестовый пользователь

После выполнения сидеров:

- **Email:** test@test.com
- **Пароль:** password

## Структура проекта

```
src/
├── app/
│   ├── Http/Controllers/       # Контроллеры
│   │   ├── Auth/               # Авторизация
│   │   ├── AlbumController.php # Список, просмотр, удаление
│   │   ├── AlbumFormController.php  # Создание / редактирование
│   │   └── AlbumAutocompleteController.php  # Last.fm API
│   ├── Models/
│   │   ├── Album.php           # Модель альбома
│   │   ├── AlbumLog.php        # Модель лога
│   │   └── User.php            # Пользователь
│   ├── Observers/
│   │   └── AlbumObserver.php   # Автоматическое логирование
│   └── Services/
│       └── LastFmService.php   # Сервис Last.fm API
├── database/migrations/        # Миграции
├── database/seeders/           # Сидеры
├── resources/views/            # Blade-шаблоны
│   ├── layouts/app.blade.php   # Основной layout
│   ├── albums/                 # Страницы альбомов
│   └── auth/                   # Авторизация
└── routes/web.php              # Маршруты
```

## API Last.fm

Ключ уже установлен

## Команды

```bash
# Очистить все кэши
docker exec nutnet-php php artisan optimize:clear

# Зайти в PHP-контейнер
docker exec -it nutnet-php sh

# Посмотреть логи
docker-compose logs -f php
```
