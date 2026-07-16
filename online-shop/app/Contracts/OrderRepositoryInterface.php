<?php
declare(strict_types=1);
namespace App\Contracts;

interface OrderRepositoryInterface
{
    public function saveOrder(int $customerId, float $amount, string $address): int;
    public function saveOrderItems(int $orderId, array $cartItems): void;
    public function getOrdersByCustomer(int $customerId): \Illuminate\Database\Eloquent\Collection;
    public function findOwnedByUser(int $orderId, int $customerId): ?\App\Models\Order;
    public function updateStatus(int $orderId, string $status): void;
}
