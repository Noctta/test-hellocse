<?php

namespace App\Services;


use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class CategoryService
{

    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return Category::query()
            ->withCount(['onlineProducts'])
            ->latest()
            ->paginate($perPage);
    }


    public function find(int $id): Category
    {
        return Category::query()
            ->withCount(['onlineProducts'])
            ->findOrFail($id);
    }


    public function create(array $data): Category
    {
        $data['image'] = $this->storeImage($data['image']);

        return Category::create($data);
    }


    public function update(Category $category, array $data): Category
    {
        if (isset($data['image'])) {
            $this->deleteImage($category->image);
            $data['image'] = $this->storeImage($data['image']);
        }

        $category->update($data);

        return $category->refresh();
    }

 
    public function delete(Category $category): void
    {
        $this->deleteImage($category->image);

        $category->delete();
    }


    private function storeImage(UploadedFile $file): string
    {
        return $file->store('categories', 'public');
    }


    private function deleteImage(string $path): void
    {
        Storage::disk('public')->delete($path);
    }
}