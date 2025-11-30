<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/categories",
     *      operationId="getCategories",
     *      tags={"Categories"},
     *      summary="Получить список категорий",
     *      description="Возвращает список всех категорий с количеством товаров",
     *      @OA\Response(
     *          response=200,
     *          description="Успешно",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Электроника"),
     *                  @OA\Property(property="products_count", type="integer", example=15)
     *              )
     *          )
     *      )
     * )
     */
    public function index()
    {
        $categories = Category::withCount('products')->get();

        return response()->json($categories);
    }
}
