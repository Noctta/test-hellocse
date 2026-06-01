<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $categoryService,
    ) {}


    public function index(): CategoryCollection
    {
        $categories = $this->categoryService->list();

        return new CategoryCollection($categories);
    }


    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->create($request->validated());

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }


    public function show(int $id): CategoryResource
    {
        $category = $this->categoryService->find($id);

        return new CategoryResource($category);
    }


    public function update(UpdateCategoryRequest $request, Category $category): CategoryResource
    {
        $category = $this->categoryService->update($category, $request->validated());

        return new CategoryResource($category);
    }


    public function destroy(Category $category): JsonResponse
    {
        $this->categoryService->delete($category);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}