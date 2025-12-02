<?php

namespace App\Http\Controllers\Api\Admin;

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
     *      path="/api/admin/products",
     *      operationId="adminGetProducts",
     *      tags={"Admin/Products"},
     *      summary="Получить список товаров (админ)",
     *      description="Возвращает список всех товаров с возможностью поиска",
     *      security={{"bearerAuth":{}}},
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
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Не авторизован"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Доступ запрещен"
     *      )
     * )
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json($products);
    }

    /**
     * @OA\Post(
     *      path="/api/admin/products",
     *      operationId="adminCreateProduct",
     *      tags={"Admin/Products"},
     *      summary="Создать новый товар (админ)",
     *      description="Создает новый товар в системе с возможностью загрузки изображения",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"category_id","name","price","stock"},
     *                  @OA\Property(property="category_id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Смартфон Samsung"),
     *                  @OA\Property(property="description", type="string", example="Описание товара", nullable=true),
     *                  @OA\Property(property="price", type="number", format="float", example=29999.99),
     *                  @OA\Property(property="stock", type="integer", example=10),
     *                  @OA\Property(property="image", type="string", format="binary", description="Изображение товара (jpeg, jpg, png, gif, webp, max: 2MB)", nullable=true),
     *                  @OA\Property(property="is_active", type="boolean", example=true)
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Товар успешно создан",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="category_id", type="integer", example=1),
     *              @OA\Property(property="name", type="string", example="Смартфон Samsung"),
     *              @OA\Property(property="price", type="number", format="float", example=29999.99),
     *              @OA\Property(property="image", type="string", example="products/image123.jpg", nullable=true)
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Не авторизован"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Доступ запрещен"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Ошибка валидации"
     *      )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $product = $this->productService->create($validated);

        return response()->json($product, 201);
    }

    /**
     * @OA\Get(
     *      path="/api/admin/products/{id}",
     *      operationId="adminGetProductById",
     *      tags={"Admin/Products"},
     *      summary="Получить товар по ID (админ)",
     *      description="Возвращает детальную информацию о товаре",
     *      security={{"bearerAuth":{}}},
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
     *              @OA\Property(property="price", type="number", format="float", example=29999.99)
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Не авторизован"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Доступ запрещен"
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

    /**
     * @OA\Post(
     *      path="/api/admin/products/{id}",
     *      operationId="adminUpdateProduct",
     *      tags={"Admin/Products"},
     *      summary="Обновить товар (админ)",
     *      description="Обновляет существующий товар с возможностью загрузки изображения. Используйте _method=PUT в form-data",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID товара",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(property="_method", type="string", example="PUT", description="HTTP метод для Laravel"),
     *                  @OA\Property(property="category_id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Смартфон Samsung"),
     *                  @OA\Property(property="description", type="string", example="Обновленное описание", nullable=true),
     *                  @OA\Property(property="price", type="number", format="float", example=27999.99),
     *                  @OA\Property(property="stock", type="integer", example=15),
     *                  @OA\Property(property="image", type="string", format="binary", description="Изображение товара (jpeg, jpg, png, gif, webp, max: 2MB)", nullable=true),
     *                  @OA\Property(property="is_active", type="boolean", example=true)
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Товар успешно обновлен"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Не авторизован"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Доступ запрещен"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Товар не найден"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Ошибка валидации"
     *      )
     * )
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'image' => 'nullable|file|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $product = $this->productService->update($product, $validated);

        return response()->json($product);
    }

    /**
     * @OA\Delete(
     *      path="/api/admin/products/{id}",
     *      operationId="adminDeleteProduct",
     *      tags={"Admin/Products"},
     *      summary="Удалить товар (админ)",
     *      description="Удаляет товар из системы",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID товара",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Товар успешно удален",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Товар удалён")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Не авторизован"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Доступ запрещен"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Товар не найден"
     *      )
     * )
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Товар удалён']);
    }
}
