# README.md

1. Скопируйте файл .env.example в файл .env:

```php
cp online-shop/.env.example online-shop/.env
```

1. Заполните переменные окружения:

```php
APP_URL=http://localhost:8081DB_CONNECTION=mysql
DB_HOST=mysql
DB_DATABASE=online_shop
DB_USERNAME=shop_user
DB_PASSWORD=123456
```

1. Поднимем контейнеры:

```php
docker compose up -d --build
```

1. Зависимости и ключ приложения:

```php
docker compose exec php-cli composer install
docker compose exec php-cli php artisan key:generate
```

1. Откройте [http://localhost:8081](http://localhost:8081/)