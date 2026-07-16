<?php

namespace App\Services;

use App\Contracts\CatalogRepositoryInterface;
use App\Helpers\Helper;

class CatalogService
{
    public function __construct(
        private CatalogRepositoryInterface $repo
    ) {}

    public function getProductsForCatalog(?int $categoryId): array
    {
        $categories = $this->repo->getCategories();

        $products = $categoryId
            ? $this->repo->getProductsByCategory($categoryId)
            : $this->repo->getProducts();

        $categoryMap = $categories->keyBy('categoryId')->map->name;

        $productIds = $products->pluck('productId')->all();

        $prices = $this->repo->getActivePrices($productIds);
        $stockQuantities = $this->repo->getStockQuantities($productIds);

        $products = $products->map(function ($product) use ($categoryMap, $prices, $stockQuantities) {
            $priceRow  = $prices->get($product->productId);
            $basePrice = $priceRow ? (float) $priceRow->price : null;
            $discountInfo = Helper::priceInfo($product, $basePrice);

            $quantity = $stockQuantities->get($product->productId)?->quantity ?? 0;
            $inStock  = $quantity > 0;

            return array_merge($product->toArray(), [
                'price'          => $discountInfo['price'],
                'original_price' => $discountInfo['original_price'],
                'has_discount'   => $discountInfo['has_discount'],
                'currency'       => $priceRow?->currency,
                'category_name'  => $categoryMap[$product->category_id] ?? '—',
                'quantity'       => $quantity,
                'in_stock'       => $inStock,
            ]);
        });

        return [
            'categories'       => $categories,
            'products'         => $products,
            'activeCategoryId' => $categoryId,
        ];
    }
}
