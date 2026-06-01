<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
    ) {}


    public function index(Request $request): ProductCollection
    {
        $categoryId = $request->query('category_id');

        $products = $this->productService->list(categoryId: $categoryId);

        return new ProductCollection($products);
    }


    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->create($request->validated());

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }


    public function show(int $id): ProductResource
    {
        $product = $this->productService->find($id);

        return new ProductResource($product);
    }


    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        $product = $this->productService->update($product, $request->validated());

        return new ProductResource($product);
    }


    public function destroy(Product $product): JsonResponse
    {
        $this->productService->delete($product);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}