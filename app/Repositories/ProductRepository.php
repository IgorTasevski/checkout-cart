<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAll(): Collection
    {
        return Product::with('creator', 'skus', 'configurations')->get();
    }

    public function findById(int $id): ?Product
    {
        return Product::find($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(int $id, array $data): Product
    {
        $product = Product::find($id);

        if ($product) {
            $product->update($data);
        }

        return $product->fresh();
    }

    public function delete(int $id): bool
    {
        $product = Product::find($id);

        if ($product) {
            return $product->delete();
        }

        return false;
    }
}
