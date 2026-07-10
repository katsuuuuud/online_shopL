<?php
declare(strict_types=1);
namespace App\Contracts;

interface CartRepositoryInterface
{
    public function getItems(?int $userId, ?string $guestId): array;
    public function addItem(?int $userId, ?string $guestId, int $productId, string $name, float $price, string $currency, int $quantity): array;
    public function removeItem(?int $userId, ?string $guestId, int $productId): array;
    public function clear(?int $userId, ?string $guestId): void;
    public function mergeGuestCartToUser(int $userId, ?string $guestId): void;
}
