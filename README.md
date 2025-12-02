# Инструкциями по развертыванию (как запустить backend и frontend)
### 2. Backend (Laravel)
```bash
cd server

# Установка зависимостей
composer install

# Копирование конфига
cp .env.example .env

# Генерация ключа
php artisan key:generate

# Настройка .env (база данных)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=shop
# DB_USERNAME=root
# DB_PASSWORD=your_password

# Создание базы данных
mysql -u root -p -e "CREATE DATABASE shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Миграции и сиды
php artisan migrate --seed

# Генерация Swagger документации
php artisan l5-swagger:generate

# Запуск сервера
php artisan serve
```

Backend будет доступен на `http://localhost:8000`
Swagger документация: `http://localhost:8000/api/documentation`

### 3. Frontend (Vue.js)
```bash
cd client

# Установка зависимостей
npm install

# Настройка .env
touch .env
# VITE_API_URL=http://localhost:8000/api

# Запуск dev сервера
npm run dev
```

## Тестовые пользователи

<b>Admin:</b> <br/>
email: admin@shop.test <br/>
password: password


## Архитектурные решения
### Backend
- **Services** — бизнес-логика вынесена из контроллеров в сервисы (`OrderService`, `ProductService`)
- **Form Requests** — валидация в отдельных классах
- **API Resources** — форматирование ответов (при необходимости)
- **Middleware** — проверка роли админа
- 
### Frontend
- **FSD (Feature-Sliced Design)** — архитектура фронтенда
- **Pinia** — state management с persist для корзины
- **Composition API** — современный подход Vue 3
- **TypeScript** — типизация