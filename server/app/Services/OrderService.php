<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function create(User $user, array $data): Order
    {
        return DB::transaction(function () use ($user, $data) {
            $total = 0;
            $orderItems = [];

            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Проверка наличия на складе
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Недостаточно товара: {$product->name}");
                }

                $itemTotal = $product->price * $item['quantity'];
                $total += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];

                // Уменьшаем остаток
                $product->decrement('stock', $item['quantity']);
            }

            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total' => $total,
                'phone' => $data['phone'],
                'address' => $data['address'],
                'comment' => $data['comment'] ?? null,
            ]);

            $order->items()->createMany($orderItems);

            return $order->load('items.product');
        });
    }

    public function updateStatus(Order $order, string $status): Order
    {
        $order->update(['status' => $status]);

        return $order;
    }
}
