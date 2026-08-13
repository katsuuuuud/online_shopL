<?php

namespace App\Repositories;

use App\Contracts\ProductAuditRepositoryInterface;
use App\Models\Stock;

class ProductAuditRepository implements ProductAuditRepositoryInterface
{
    public function decrementStock(int $productId, int $quantity): bool
    {
        $updated = Stock::where('product_id', $productId)
            ->where('quantity', '>=', $quantity)
            ->decrement('quantity', $quantity);

        return $updated > 0;
    }
}
