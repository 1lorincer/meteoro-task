<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {}

    /**
     * @OA\Get(
     *      path="/api/products",
     *      operationId="getProducts",
     *      tags={"Products"},
     *      summary="Получить список товаров",
     *      description="Возвращает список товаров с фильтрацией и пагинацией",
     *      @OA\Parameter(
     *          name="category_id",
     *          in="query",
     *          description="ID категории для фильтрации",
     *          required=false,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Parameter(
     *          name="search",
     *          in="query",
     *          description="Поиск по названию товара",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Успешно",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="category_id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Смартфон"),
     *                      @OA\Property(property="description", type="string", example="Описание товара"),
     *                      @OA\Property(property="price", type="number", format="float", example=29999.99),
     *                      @OA\Property(property="stock", type="integer", example=10),
     *                      @OA\Property(property="is_active", type="boolean", example=true)
     *                  )
     *              ),
     *              @OA\Property(property="current_page", type="integer", example=1),
     *              @OA\Property(property="total", type="integer", example=50)
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $products = $this->productService->getList($request->all());

        return response()->json($products);
    }

    /**
     * @OA\Get(
     *      path="/api/products/{id}",
     *      operationId="getProductById",
     *      tags={"Products"},
     *      summary="Получить товар по ID",
     *      description="Возвращает детальную информацию о товаре",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID товара",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Успешно",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="category_id", type="integer", example=1),
     *              @OA\Property(property="name", type="string", example="Смартфон"),
     *              @OA\Property(property="description", type="string", example="Описание товара"),
     *              @OA\Property(property="price", type="number", format="float", example=29999.99),
     *              @OA\Property(property="stock", type="integer", example=10),
     *              @OA\Property(property="is_active", type="boolean", example=true),
     *              @OA\Property(property="category", type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Электроника")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Товар не найден"
     *      )
     * )
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        return response()->json($product);
    }
}
