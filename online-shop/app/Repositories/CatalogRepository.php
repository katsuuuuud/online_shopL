<?php

namespace App\Repositories;

use App\Contracts\CatalogRepositoryInterface;
use App\Models\Category;
use App\Models\Price;
use App\Models\Product;
use App\Models\ProductAudit;
use Illuminate\Database\Eloquent\Collection;

class CatalogRepository implements CatalogRepositoryInterface
{
    public function getCategories(): Collection
    {
        return Category::all();
    }

    public function getProducts(): Collection
    {
        return Product::all();
    }

    public function getProductsByCategory(int $categoryId): Collection
    {
        return Product::where('category_id', $categoryId)->get();
    }

    public function getProductById(int $id): ?Product
    {
        return Product::find($id);
    }

    public function getActivePrice(int $productId): ?Price
    {
        return Price::where('product_id', $productId)
            ->where('is_active', 1)
            ->first();
    }

    public function getActivePrices(array $productIds): Collection
    {
        return Price::whereIn('product_id', $productIds)
            ->where('is_active', 1)
            ->get()
            ->keyBy('product_id');
    }

    public function getStockQuantities(array $productIds): Collection
    {
        return ProductAudit::whereIn('product_id', $productIds)
            ->get()
            ->keyBy('product_id');
    }
}
