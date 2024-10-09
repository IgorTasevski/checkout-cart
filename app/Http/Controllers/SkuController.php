<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sku\DecrementStockRequest;
use App\Http\Requests\Sku\StoreSkuRequest;
use App\Http\Requests\Sku\UpdateSkuRequest;
use App\Repositories\Contracts\SKURepositoryInterface;
use Illuminate\Http\JsonResponse;

class SkuController extends Controller
{
    public function __construct(private readonly SKURepositoryInterface $skuRepository)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json($this->skuRepository->getAll());
    }

    public function show($id): JsonResponse
    {
        $sku = $this->skuRepository->findById($id);

        if ($sku) {
            return response()->json($sku);
        }

        return response()->json(['error' => 'SKU not found'], 404);
    }

    public function store(StoreSkuRequest $request): JsonResponse
    {
        $sku = $this->skuRepository->create($request->validated());
        return response()->json($sku, 201);
    }

    public function update(UpdateSkuRequest $request, $id): JsonResponse
    {
        $updated = $this->skuRepository->update($id, $request->validated());

        if ($updated) {
            return response()->json(['message' => 'SKU updated successfully']);
        }

        return response()->json(['error' => 'SKU not found'], 404);
    }

    public function destroy($id): JsonResponse
    {
        $deleted = $this->skuRepository->delete($id);

        if ($deleted) {
            return response()->json(['message' => 'SKU deleted successfully']);
        }

        return response()->json(['error' => 'SKU not found'], 404);
    }

    public function decrementStock(DecrementStockRequest $request, $id): JsonResponse
    {

        if ($this->skuRepository->decrementStock($id, $request->validated())) {
            return response()->json(['message' => 'Stock decremented successfully']);
        }

        return response()->json(['error' => 'Insufficient stock or SKU not found'], 400);
    }
}
