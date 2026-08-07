<?php
declare(strict_types=1);
namespace App\Contracts;

interface ProductAuditRepositoryInterface
{
    public function decrementStock(int $productId, int $quantity): bool;
}
