<?php

namespace App\Contracts;

interface CatalogRepositoryInterface
{
    public function getCategories(): \Illuminate\Database\Eloquent\Collection;
    public function getProducts(): \Illuminate\Database\Eloquent\Collection;
    public function getProductsByCategory(int $categoryId): \Illuminate\Database\Eloquent\Collection;
    public function getProductById(int $id): ?\App\Models\Product;
    public function getActivePrice(int $productId): ?\App\Models\Price;
}
