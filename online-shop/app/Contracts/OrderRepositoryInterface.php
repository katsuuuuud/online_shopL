<?php
declare(strict_types=1);
namespace App\Contracts;

interface OrderRepositoryInterface
{
    public function saveOrder(int $customerId, float $amount, string $address): int;
    public function saveOrderItems(int $orderId, int $customerId, array $cartItems): void;
    public function getOrdersByCustomer(int $customerId): \Illuminate\Database\Eloquent\Collection;
}
