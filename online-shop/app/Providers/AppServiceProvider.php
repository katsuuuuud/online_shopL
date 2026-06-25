<?php

namespace App\Providers;

use App\Contracts\CartRepositoryInterface;
use App\Contracts\CatalogRepositoryInterface;
use App\Contracts\OrderRepositoryInterface;
use App\Repositories\CartRepository;
use App\Repositories\CatalogRepository;
use App\Repositories\OrderRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CartRepositoryInterface::class,    CartRepository::class);
        $this->app->bind(CatalogRepositoryInterface::class, CatalogRepository::class);
        $this->app->bind(OrderRepositoryInterface::class,   OrderRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
