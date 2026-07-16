<?php
declare(strict_types=1);
namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface CatalogRepositoryInterface
{
    public function getCategories(): Collection;
    public function getProducts(): Collection;
    public function getProductsByCategory(int $categoryId): Collection;
    public function getProductById(int $id): ?\App\Models\Product;
    public function getActivePrice(int $productId): ?\App\Models\Price;
    public function getActivePrices(array $productIds): Collection;
    public function getStockQuantities(array $productIds): Collection;
}
