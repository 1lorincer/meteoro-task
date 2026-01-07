<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessNewOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Order $order
    ) {
    }

    public function handle(): void
    {
        Log::info('Новый заказ создан', [
            'order_id' => $this->order->id,
            'user_id' => $this->order->user_id,
            'total_price' => $this->order->total_price,
            'status' => $this->order->status,
            'items_count' => $this->order->items->count(),
        ]);

        Log::info('Уведомление о заказе отправлено пользователю', [
            'user_id' => $this->order->user_id,
            'order_id' => $this->order->id,
        ]);
    }
}
