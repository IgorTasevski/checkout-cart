<?php

namespace App\Repositories;

use App\Models\SKU;
use App\Repositories\Contracts\SKURepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SkuRepository implements SKURepositoryInterface
{
    public function getAll(): Collection
    {
        return SKU::with('product')->get();
    }

    public function findById(int $id): ?SKU
    {
        return SKU::find($id);
    }

    public function findBySkuAndProductId(string $code, int $productId): ?SKU
    {
        return SKU::where('code', $code)->where('product_id', $productId)->first();
    }

    public function create(array $data): SKU
    {
        return SKU::create($data)->refresh('product');
    }

    public function update(int $id, array $data): SKU
    {
        $sku = SKU::find($id);

        if ($sku) {
            return $sku->update($data);
        }

        return $sku->refresh('product');
    }

    public function delete(int $id): bool
    {
        $sku = SKU::find($id);

        if ($sku) {
            return $sku->delete();
        }

        return false;
    }

    public function decrementStock(int $id, int $amount): bool
    {
        $sku = SKU::find($id);

        if ($sku && $sku->amount >= $amount) {
            $sku->amount -= $amount;
            return $sku->save();
        }

        return false;
    }
}
