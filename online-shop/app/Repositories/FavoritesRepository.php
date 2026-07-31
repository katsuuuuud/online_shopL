<?php

namespace App\Repositories;

use App\Contracts\FavoritesRepositoryInterface;
use App\Models\Favorite;

class FavoritesRepository implements FavoritesRepositoryInterface
{
    public function add(int $userId, int $productId): void
    {
        Favorite::firstOrCreate([
            'user_id'    => $userId,
            'product_id' => $productId,
        ]);
    }

    public function remove(int $userId, int $productId): void
    {
        Favorite::where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete();
    }

    public function getProductIds(int $userId): array
    {
        return Favorite::where('user_id', $userId)
            ->pluck('product_id')
            ->all();
    }
}
