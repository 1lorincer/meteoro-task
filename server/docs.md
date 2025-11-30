# Документация API Swagger

## Обзор

Документация API для интернет-магазина создана с использованием Swagger (OpenAPI). Она предоставляет интерактивный интерфейс для тестирования всех API эндпоинтов.

## Доступ к документации

После запуска приложения Swagger UI доступен по адресу:

```
http://localhost:8000/api/documentation
```

или

```
http://localhost/api/documentation
```

## Структура API

### 1. Аутентификация (Auth)
- **POST /api/register** - Регистрация нового пользователя
- **POST /api/login** - Вход в систему
- **POST /api/logout** - Выход из системы (требует авторизации)
- **GET /api/user** - Получить данные текущего пользователя (требует авторизации)

### 2. Категории (Categories)
- **GET /api/categories** - Получить список всех категорий

### 3. Товары (Products)
- **GET /api/products** - Получить список товаров с фильтрацией
- **GET /api/products/{id}** - Получить товар по ID

### 4. Заказы пользователей (Orders)
Все эндпоинты требуют авторизации:
- **GET /api/orders** - Получить список заказов текущего пользователя
- **POST /api/orders** - Создать новый заказ
- **GET /api/orders/{id}** - Получить заказ по ID

### 5. Управление товарами - Админ (Admin/Products)
Все эндпоинты требуют авторизации с ролью администратора:
- **GET /api/admin/products** - Получить список всех товаров
- **POST /api/admin/products** - Создать новый товар
- **GET /api/admin/products/{id}** - Получить товар по ID
- **PUT /api/admin/products/{id}** - Обновить товар
- **DELETE /api/admin/products/{id}** - Удалить товар

### 6. Управление заказами - Админ (Admin/Orders)
Все эндпоинты требуют авторизации с ролью администратора:
- **GET /api/admin/orders** - Получить список всех заказов
- **GET /api/admin/orders/{id}** - Получить заказ по ID
- **PATCH /api/admin/orders/{id}/status** - Обновить статус заказа

## Авторизация в Swagger

Для тестирования защищенных эндпоинтов:

1. Сначала используйте эндпоинт **POST /api/register** или **POST /api/login** для получения токена
2. Скопируйте значение `token` из ответа
3. Нажмите кнопку **Authorize** (замок) в верхней части Swagger UI
4. Введите токен в формате: `Bearer YOUR_TOKEN_HERE`
5. Нажмите **Authorize**, затем **Close**
6. Теперь вы можете тестировать защищенные эндпоинты

## Генерация документации

Для регенерации документации после изменения аннотаций:

```bash
php artisan l5-swagger:generate
```

## Примеры использования

### Регистрация пользователя

```json
POST /api/register
{
  "name": "Иван Иванов",
  "email": "ivan@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### Создание заказа

```json
POST /api/orders
Authorization: Bearer YOUR_TOKEN

{
  "items": [
    {
      "product_id": 1,
      "quantity": 2
    },
    {
      "product_id": 2,
      "quantity": 1
    }
  ],
  "phone": "+7 (999) 123-45-67",
  "address": "ул. Ленина, д. 1, кв. 5",
  "comment": "Позвоните за час до доставки"
}
```

### Создание товара (админ)

```json
POST /api/admin/products
Authorization: Bearer ADMIN_TOKEN

{
  "category_id": 1,
  "name": "Смартфон Samsung Galaxy S23",
  "description": "Флагманский смартфон с отличной камерой",
  "price": 79999.99,
  "stock": 15,
  "is_active": true
}
```

### Обновление статуса заказа (админ)

```json
PATCH /api/admin/orders/1/status
Authorization: Bearer ADMIN_TOKEN

{
  "status": "processing"
}
```

## Статусы заказов

Допустимые значения статуса заказа:
- `pending` - В ожидании
- `processing` - В обработке
- `completed` - Завершен
- `cancelled` - Отменен

## Коды ответов

- **200** - Успешный запрос
- **201** - Ресурс успешно создан
- **401** - Не авторизован (требуется токен)
- **403** - Доступ запрещен (недостаточно прав)
- **404** - Ресурс не найден
- **422** - Ошибка валидации данных

## Конфигурация

Файл конфигурации Swagger находится в:
```
config/l5-swagger.php
```

Основные настройки:
- Маршрут документации: `/api/documentation`
- Хранение документации: `storage/api-docs/`
- Сканируемые директории: `app/`

## Полезные команды

```bash
# Регенерация документации
php artisan l5-swagger:generate

# Очистка кэша документации
php artisan cache:clear

# Просмотр списка маршрутов
php artisan route:list
```

## Технические детали

- **Пакет**: darkaonline/l5-swagger
- **Версия OpenAPI**: 3.0
- **Формат**: JSON
- **Схема авторизации**: Bearer Token (JWT)

## Расширение документации

Для добавления новых эндпоинтов:

1. Добавьте PHPDoc аннотации над методами контроллера
2. Используйте теги `@OA\` для описания эндпоинта
3. Запустите `php artisan l5-swagger:generate`
4. Обновите страницу документации в браузере

Пример аннотации:

```php
/**
 * @OA\Get(
 *      path="/api/example",
 *      operationId="getExample",
 *      tags={"Example"},
 *      summary="Краткое описание",
 *      description="Подробное описание",
 *      @OA\Response(
 *          response=200,
 *          description="Успешный ответ"
 *      )
 * )
 */
public function example()
{
    // код метода
}
```

## Поддержка

Для получения дополнительной информации:
- [Документация L5-Swagger](https://github.com/DarkaOnLine/L5-Swagger)
- [Спецификация OpenAPI](https://swagger.io/specification/)
