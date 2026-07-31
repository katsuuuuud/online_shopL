<?php

namespace App\Contracts;

interface FavoritesRepositoryInterface
{
    public function add(int $userId, int $productId): void;

    public function remove(int $userId, int $productId): void;

    public function getProductIds(int $userId): array;
}
