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
        $data['image'] = $this->storeImage($data['image']);

        $product = Product::create($data);

        return $product->load('category');
    }


    public function update(Product $product, array $data): Product
    {
        if (isset($data['image'])) {
            $this->deleteImage($product->image);
            $data['image'] = $this->storeImage($data['image']);
        }

        $product->update($data);

        return $product->refresh()->load('category');
    }


    public function delete(Product $product): void
    {
        $this->deleteImage($product->image);

        $product->delete();
    }


    private function storeImage(UploadedFile $file): string
    {
        return $file->store('products', 'public');
    }


    private function deleteImage(string $path): void
    {
        Storage::disk('public')->delete($path);
    }
}