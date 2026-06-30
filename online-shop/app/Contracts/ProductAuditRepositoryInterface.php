<?php

namespace App\Contracts;

interface ProductAuditRepositoryInterface
{
    public function decrementStock(int $productId, int $quantity): bool;
}
