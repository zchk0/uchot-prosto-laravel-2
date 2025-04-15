# Knowledge Base на Laravel

## Шаги по установке и запуску проекта

Установите PHP-зависимости:
```bash
composer install
```

Скопируйте шаблон файла окружения и настройте параметры подключения к базе данных:
```bash
cp .env.example .env
```

Сгенерируйте новый ключ для приложения:
```bash
php artisan key:generate
```

Создайте таблицы в базе данных:
```bash
php artisan migrate
```

Заполните базу данных стартовыми данными (темы и подтемы):
```bash
php artisan db:seed
```