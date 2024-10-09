<?php

namespace App\Providers;

use App\Repositories\ConfigurationRepository;
use App\Repositories\Contracts\ConfigurationRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\SKURepositoryInterface;
use App\Repositories\ProductRepository;
use App\Repositories\SkuRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(SKURepositoryInterface::class, SKURepository::class);
        $this->app->bind(SkuRepositoryInterface::class, SkuRepository::class);
        $this->app->bind(ConfigurationRepositoryInterface::class, ConfigurationRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
