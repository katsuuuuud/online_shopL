<?php

namespace App\Providers;

use App\Contracts\CartRepositoryInterface;
use App\Contracts\CatalogRepositoryInterface;
use App\Contracts\OrderRepositoryInterface;
use App\Contracts\ProductAuditRepositoryInterface;
use App\Repositories\CartRepository;
use App\Repositories\CatalogRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductAuditRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CartRepositoryInterface::class,         CartRepository::class);
        $this->app->bind(CatalogRepositoryInterface::class,      CatalogRepository::class);
        $this->app->bind(OrderRepositoryInterface::class,        OrderRepository::class);
        $this->app->bind(ProductAuditRepositoryInterface::class, ProductAuditRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
