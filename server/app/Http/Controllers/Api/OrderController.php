<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($orders);
    }

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

    public function show(Request $request, $id)
    {
        $order = $request->user()
            ->orders()
            ->with('items.product')
            ->findOrFail($id);

        return response()->json($order);
    }
}
