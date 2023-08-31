## Setup project

### Install dependencies
```bash
composer install
npm install
copy .env.example .env
php artisan key:generate
```

### Setup .env
Change the following variables in the .env file:
```bash
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=database_name
DB_USERNAME=username
DB_PASSWORD=password
```

### Run migrations
```bash
php artisan migrate
php artisan db:seed
```
