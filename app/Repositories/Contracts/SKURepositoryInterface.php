<?php

namespace App\Repositories\Contracts;

use App\Models\SKU;
use Illuminate\Database\Eloquent\Collection;

interface SKURepositoryInterface
{
    public function getAll(): Collection;
    public function findById(int $id): ?SKU;
    public function findBySkuAndProductId(string $code, int $productId): ?SKU;
    public function create(array $data): SKU;
    public function update(int $id, array $data): SKU;
    public function delete(int $id): bool;
    public function decrementStock(int $id, int $amount): bool;
}

