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
        ], [
            'created_at' => now(),
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
            ->orderByDesc('created_at')
            ->pluck('product_id')
            ->all();
    }
}
