<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\Product\ProductResource;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(private readonly ProductRepositoryInterface $productRepository)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json(ProductResource::collection($this->productRepository->getAll()));
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productRepository->create($request->validated());

        return response()->json(new ProductResource($product));
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->productRepository->findById($id);

        return response()->json(new ProductResource($product));
    }

    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        $product = $this->productRepository->update($id, $request->validated());

        return response()->json(new ProductResource($product));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->productRepository->delete($id);

        return response()->json(['success' => true]);
    }
}
