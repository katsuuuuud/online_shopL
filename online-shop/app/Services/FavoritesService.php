<?php

namespace App\Services;

use App\Contracts\CatalogRepositoryInterface;
use App\Contracts\FavoritesRepositoryInterface;
use App\Exceptions\DomainException;
use App\Helpers\Helper;

class FavoritesService
{
    public function __construct(
        private FavoritesRepositoryInterface $favoritesRepo,
        private CatalogRepositoryInterface    $catalogRepo,
    ) {}

    public function add(int $userId, int $productId): void
    {
        $product = $this->catalogRepo->getProductById($productId);
        if (! $product) {
            throw new DomainException('Товар не найден', 404);
        }

        $this->favoritesRepo->add($userId, $productId);
    }

    public function remove(int $userId, int $productId): void
    {
        $this->favoritesRepo->remove($userId, $productId);
    }

    public function getItems(int $userId): array
    {
        $productIds = $this->favoritesRepo->getProductIds($userId);
        if (empty($productIds)) {
            return [];
        }

        $categories  = $this->catalogRepo->getCategories();
        $categoryMap = $categories->keyBy('categoryId')->map->name;

        $prices          = $this->catalogRepo->getActivePrices($productIds);
        $stockQuantities = $this->catalogRepo->getStockQuantities($productIds);

        $items = [];
        foreach ($productIds as $productId) {
            $product = $this->catalogRepo->getProductById($productId);
            if (! $product) {
                continue;
            }

            $priceRow  = $prices->get($productId);
            $basePrice = $priceRow ? (float) $priceRow->price : null;
            $discountInfo = Helper::priceInfo($product, $basePrice);

            $quantity = $stockQuantities->get($productId)?->quantity ?? 0;

            $items[] = array_merge($product->toArray(), [
                'price'          => $discountInfo['price'],
                'original_price' => $discountInfo['original_price'],
                'has_discount'   => $discountInfo['has_discount'],
                'currency'       => $priceRow?->currency,
                'category_name'  => $categoryMap[$product->category_id] ?? '—',
                'quantity'       => $quantity,
                'in_stock'       => $quantity > 0,
            ]);
        }

        return $items;
    }
}
