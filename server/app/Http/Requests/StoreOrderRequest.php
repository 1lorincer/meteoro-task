<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'comment' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Корзина пуста',
            'items.min' => 'Добавьте хотя бы один товар',
            'phone.required' => 'Укажите телефон',
            'address.required' => 'Укажите адрес доставки',
        ];
    }
}
