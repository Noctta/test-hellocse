<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductService
{

    public function list(?int $categoryId = null, int $perPage = 15): LengthAwarePaginator
    {
        return Product::query()
            ->with('category')
            ->when($categoryId, fn ($query, int $categoryId) => $query->where('category_id', $categoryId))
            ->latest()
            ->paginate($perPage);
    }


    public function find(int $id): Product
    {
        return Product::query()
            ->with('category')
            ->findOrFail($id);
    }


    public function create(array $data): Product
    {
        $product = Product::create($data);

        return $product->load('category');
    }


    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product->refresh()->load('category');
    }


    public function delete(Product $product): void
    {
        $product->delete();
    }
}