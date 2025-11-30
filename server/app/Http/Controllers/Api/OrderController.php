<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/orders",
     *      operationId="getUserOrders",
     *      tags={"Orders"},
     *      summary="Получить заказы текущего пользователя",
     *      description="Возвращает список заказов авторизованного пользователя",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Успешно",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="user_id", type="integer", example=1),
     *                      @OA\Property(property="status", type="string", example="pending"),
     *                      @OA\Property(property="total", type="number", format="float", example=59999.98),
     *                      @OA\Property(property="phone", type="string", example="+7 (999) 123-45-67"),
     *                      @OA\Property(property="address", type="string", example="ул. Ленина, д. 1"),
     *                      @OA\Property(property="items", type="array", @OA\Items(
     *                          @OA\Property(property="id", type="integer", example=1),
     *                          @OA\Property(property="product_id", type="integer", example=1),
     *                          @OA\Property(property="quantity", type="integer", example=2),
     *                          @OA\Property(property="price", type="number", format="float", example=29999.99)
     *                      ))
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Не авторизован"
     *      )
     * )
     */
    public function index(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($orders);
    }

    /**
     * @OA\Post(
     *      path="/api/orders",
     *      operationId="createOrder",
     *      tags={"Orders"},
     *      summary="Создать новый заказ",
     *      description="Создает новый заказ для авторизованного пользователя",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"items","phone","address"},
     *              @OA\Property(property="items", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="product_id", type="integer", example=1),
     *                      @OA\Property(property="quantity", type="integer", example=2)
     *                  )
     *              ),
     *              @OA\Property(property="phone", type="string", example="+7 (999) 123-45-67"),
     *              @OA\Property(property="address", type="string", example="ул. Ленина, д. 1"),
     *              @OA\Property(property="comment", type="string", example="Комментарий к заказу", nullable=true)
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Заказ успешно создан",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="user_id", type="integer", example=1),
     *              @OA\Property(property="status", type="string", example="pending"),
     *              @OA\Property(property="total", type="number", format="float", example=59999.98)
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Не авторизован"
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
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'phone' => 'required|string',
            'address' => 'required|string',
            'comment' => 'nullable|string',
        ]);

        $order = DB::transaction(function () use ($request, $validated) {
            $total = 0;
            $orderItems = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $itemTotal = $product->price * $item['quantity'];
                $total += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];
            }

            $order = Order::create([
                'user_id' => $request->user()->id,
                'status' => 'pending',
                'total' => $total,
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'comment' => $validated['comment'] ?? null,
            ]);

            $order->items()->createMany($orderItems);

            return $order;
        });

        return response()->json($order->load('items.product'), 201);
    }

    /**
     * @OA\Get(
     *      path="/api/orders/{id}",
     *      operationId="getUserOrderById",
     *      tags={"Orders"},
     *      summary="Получить заказ по ID",
     *      description="Возвращает детальную информацию о заказе текущего пользователя",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID заказа",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Успешно",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="user_id", type="integer", example=1),
     *              @OA\Property(property="status", type="string", example="pending"),
     *              @OA\Property(property="total", type="number", format="float", example=59999.98),
     *              @OA\Property(property="phone", type="string", example="+7 (999) 123-45-67"),
     *              @OA\Property(property="address", type="string", example="ул. Ленина, д. 1")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Не авторизован"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Заказ не найден"
     *      )
     * )
     */
    public function show(Request $request, $id)
    {
        $order = $request->user()
            ->orders()
            ->with('items.product')
            ->findOrFail($id);

        return response()->json($order);
    }
}
