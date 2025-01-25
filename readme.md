# URL Shortener

Сервис для создания коротких URL-адресов на PHP/Slim Framework.

## Возможности
- Создание коротких ссылок из длинных URL
- Автоматическая генерация уникальных кодов
- Перенаправление по короткой ссылке
- Валидация входящих URL

## Требования
- PHP 8.0+
- Composer
- SQLite3

## Установка
```bash
git clone https://github.com/your-repo/url-shortener.git
cd url-shortener
composer install
```

## API Endpoints

### POST /create
Создание короткой ссылки:
```json
{
    "url": "https://example.com/very/long/url"
}
```

### GET /{code}
Перенаправление на оригинальный URL

## Конфигурация
База данных SQLite создается автоматически в `src/Database/database.sqlite`

## Деплой на Railway.com

### 1. Подготовка файлов
Добавьте в корень проекта:

`.railway.toml`:
```toml
[build]
builder = "nixpacks"
buildCommand = "composer install"

[deploy]
startCommand = "php -S 0.0.0.0:$PORT index.php"
healthcheckPath = "/"
```

`Procfile`:
```
web: php -S 0.0.0.0:$PORT index.php
```

### 2. Деплой через веб-интерфейс
1. Зайти на railway.com
2. New Project -> Deploy from GitHub repo
3. Выбрать репозиторий url-shortener
4. Variables:
  - PORT=8080 
  - PHP_VERSION=8.1
5. Settings -> Domains -> Generate Domain
6. Settings -> Start Command: `php -S 0.0.0.0:$PORT index.php`

### 3. Настройка Railway
1. Создайте проект через GitHub
2. Добавьте переменные:
   - PORT=8080
   - PHP_VERSION=8.1
3. Проверьте Start Command
4. Сгенерируйте домен

### 4. Тестирование
```bash
# Создание короткой ссылки
curl -X POST https://your-app.railway.app/create \
-H "Content-Type: application/json" \
-d '{"url":"https://example.com"}'

# Проверьте редирект
```

## Лицензия
MIT License
