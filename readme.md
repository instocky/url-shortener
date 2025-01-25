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

## Лицензия
MIT License
