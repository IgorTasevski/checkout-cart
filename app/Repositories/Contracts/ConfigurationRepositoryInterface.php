<?php

namespace App\Repositories\Contracts;

use App\Models\Configuration;
use Illuminate\Database\Eloquent\Collection;

interface ConfigurationRepositoryInterface
{
    public function getAll(): Collection;
    public function findById(int $id): ?Configuration;
    public function getMealDealConfigurations(): Collection;
    public function create(array $data): Configuration;
    public function update(int $id, array $data): Configuration;
    public function delete(int $id): bool;
}

