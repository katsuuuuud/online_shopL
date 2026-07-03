1. Перейдите в папку online-shop/:

    `cd online-shop`

2. Скопируйте файл .env.example в файл .env:

    `cp online-shop/.env.example online-shop/.env`
3. Заполните переменные окружения:
`APP_URL=http://localhost:8081
DB_CONNECTION=mysql
DB_HOST=mysql
DB_DATABASE=online_shop
DB_USERNAME=shop_user
DB_PASSWORD=123456`