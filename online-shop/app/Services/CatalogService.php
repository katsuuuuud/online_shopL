<?php

namespace App\Services;

use App\Contracts\CatalogRepositoryInterface;

class CatalogService
{
    public function __construct(
        private CatalogRepositoryInterface $repo
    ) {}

    public function getProductsForCatalog(?int $categoryId): array
    {
        $categories = $this->repo->getCategories();
        $products   = $categoryId
            ? $this->repo->getProductsByCategory($categoryId)
            : $this->repo->getProducts();

        $categoryMap = $categories->keyBy('categoryId')->map->name;

        $products = $products->map(function ($product) use ($categoryMap) {
            $price = $this->repo->getActivePrice($product->productId);

            return array_merge($product->toArray(), [
                'price'         => $price?->price,
                'currency'      => $price?->currency,
                'category_name' => $categoryMap[$product->category_id] ?? '—',
            ]);
        });

        return [
            'categories'       => $categories,
            'products'         => $products,
            'activeCategoryId' => $categoryId,
        ];
    }
}
