<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

    /**
     * @OA\Get(
     *      path="/api/admin/orders",
     *      operationId="adminGetOrders",
     *      tags={"Admin/Orders"},
     *      summary="Получить список всех заказов (админ)",
     *      description="Возвращает список всех заказов с возможностью фильтрации по статусу",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="status",
     *          in="query",
     *          description="Фильтр по статусу заказа",
     *          required=false,
     *          @OA\Schema(type="string", enum={"pending", "processing", "completed", "cancelled"})
     *      ),
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
     *                      @OA\Property(property="user", type="object",
     *                          @OA\Property(property="id", type="integer", example=1),
     *                          @OA\Property(property="name", type="string", example="Иван Иванов"),
     *                          @OA\Property(property="email", type="string", example="ivan@example.com")
     *                      )
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
        $query = Order::with(['user', 'items.product']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json($orders);
    }

    /**
     * @OA\Get(
     *      path="/api/admin/orders/{id}",
     *      operationId="adminGetOrderById",
     *      tags={"Admin/Orders"},
     *      summary="Получить заказ по ID (админ)",
     *      description="Возвращает детальную информацию о заказе",
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
     *              @OA\Property(property="address", type="string", example="ул. Ленина, д. 1"),
     *              @OA\Property(property="user", type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Иван Иванов")
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
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Заказ не найден"
     *      )
     * )
     */
    public function show($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);

        return response()->json($order);
    }

    /**
     * @OA\Patch(
     *      path="/api/admin/orders/{id}/status",
     *      operationId="adminUpdateOrderStatus",
     *      tags={"Admin/Orders"},
     *      summary="Обновить статус заказа (админ)",
     *      description="Изменяет статус заказа",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID заказа",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"status"},
     *              @OA\Property(property="status", type="string", enum={"pending", "processing", "completed", "cancelled"}, example="processing")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Статус успешно обновлен",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="status", type="string", example="processing")
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
     *          description="Заказ не найден"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Ошибка валидации"
     *      )
     * )
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order = $this->orderService->updateStatus($order, $validated['status']);

        return response()->json($order);
    }
}
