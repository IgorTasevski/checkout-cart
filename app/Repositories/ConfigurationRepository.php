<?php

namespace App\Repositories;

use App\Models\Configuration;
use App\Repositories\Contracts\ConfigurationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ConfigurationRepository implements ConfigurationRepositoryInterface
{
    public function getAll(): Collection
    {
        return Configuration::where('active', true)->with('product')->get();
    }

    public function findById(int $id): ?Configuration
    {
        return Configuration::find($id);
    }

    public function getMealDealConfigurations(): Collection
    {
        return Configuration::where([
            'rule_type' => 'meal_deal',
            'active' => true
        ])->get();
    }

    public function create(array $data): Configuration
    {
        return Configuration::create($data);
    }

    public function update(int $id, array $data): Configuration
    {
        $configuration = Configuration::find($id);

        if ($configuration) {
            return $configuration->update($data);
        }

        return $configuration;
    }

    public function delete(int $id): bool
    {
        $configuration = Configuration::find($id);

        if ($configuration) {
            return $configuration->delete();
        }

        return false;
    }
}
