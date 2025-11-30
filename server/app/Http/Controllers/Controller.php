<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Meteoro Task API",
 *      description="API документация для интернет-магазина",
 *      @OA\Contact(
 *          email="admin@meteoro.com"
 *      ),
 *      @OA\License(
 *          name="MIT",
 *          url="https://opensource.org/licenses/MIT"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="API Server"
 * )
 *
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT"
 * )
 *
 * @OA\Tag(
 *     name="Auth",
 *     description="Аутентификация и регистрация"
 * )
 *
 * @OA\Tag(
 *     name="Products",
 *     description="Публичные операции с товарами"
 * )
 *
 * @OA\Tag(
 *     name="Categories",
 *     description="Операции с категориями"
 * )
 *
 * @OA\Tag(
 *     name="Orders",
 *     description="Заказы пользователей"
 * )
 *
 * @OA\Tag(
 *     name="Admin/Products",
 *     description="Управление товарами (только администратор)"
 * )
 *
 * @OA\Tag(
 *     name="Admin/Orders",
 *     description="Управление заказами (только администратор)"
 * )
 */
abstract class Controller
{
    //
}
