<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function getList(array $filters = []): LengthAwarePaginator
    {
        $query = Product::with('category')->where('is_active', true);

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($filters['per_page'] ?? 12);
    }

    public function create(array $data): Product
    {
        $data['slug'] = Str::slug($data['name']);

        if (isset($data['image'])) {
            $data['image'] = $this->handleImageUpload($data['image']);
        }

        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if (isset($data['image'])) {
            // Удаляем старое изображение если есть
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $this->handleImageUpload($data['image']);
        }

        $product->update($data);

        return $product->fresh();
    }

    private function handleImageUpload($image): string
    {
        $path = $image->store('products', 'public');
        return $path;
    }
}
