<?php

namespace Database\Seeders;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $products = [
            ['name' => 'iPhone 15 Pro', 'price' => 499990, 'category' => 'Смартфоны', 'stock' => 10],
            ['name' => 'Samsung Galaxy S24', 'price' => 399990, 'category' => 'Смартфоны', 'stock' => 15],
            ['name' => 'Xiaomi 14', 'price' => 299990, 'category' => 'Смартфоны', 'stock' => 20],
            ['name' => 'MacBook Pro 14', 'price' => 899990, 'category' => 'Ноутбуки', 'stock' => 5],
            ['name' => 'ASUS ROG Strix', 'price' => 649990, 'category' => 'Ноутбуки', 'stock' => 8],
            ['name' => 'Lenovo ThinkPad X1', 'price' => 549990, 'category' => 'Ноутбуки', 'stock' => 12],
            ['name' => 'AirPods Pro 2', 'price' => 129990, 'category' => 'Наушники', 'stock' => 25],
            ['name' => 'Sony WH-1000XM5', 'price' => 149990, 'category' => 'Наушники', 'stock' => 18],
            ['name' => 'Samsung Galaxy Buds 2', 'price' => 59990, 'category' => 'Наушники', 'stock' => 30],
            ['name' => 'iPad Pro 12.9', 'price' => 599990, 'category' => 'Планшеты', 'stock' => 7],
            ['name' => 'Samsung Galaxy Tab S9', 'price' => 449990, 'category' => 'Планшеты', 'stock' => 10],
            ['name' => 'Apple Watch Series 9', 'price' => 249990, 'category' => 'Аксессуары', 'stock' => 15],
            ['name' => 'Чехол для iPhone 15', 'price' => 9990, 'category' => 'Аксессуары', 'stock' => 50],
            ['name' => 'Зарядка MagSafe', 'price' => 24990, 'category' => 'Аксессуары', 'stock' => 35],
            ['name' => 'Кабель USB-C', 'price' => 4990, 'category' => 'Аксессуары', 'stock' => 100],
        ];

        foreach ($products as $item) {
            $category = Category::where('name', $item['category'])->first();

            Product::create([
                'category_id' => $category->id,
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'description' => 'Описание товара ' . $item['name'],
                'price' => $item['price'],
                'stock' => $item['stock'],
                'is_active' => true,
            ]);
        }
    }
}
