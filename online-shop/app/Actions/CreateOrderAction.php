<?php
declare(strict_types=1);
namespace App\Actions;

use App\Contracts\CartRepositoryInterface;
use App\Contracts\OrderRepositoryInterface;
use App\Contracts\ProductAuditRepositoryInterface;
use App\Exceptions\DomainException;
use App\Helpers\Helper;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateOrderAction
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private CartRepositoryInterface $cartRepository,
        private ProductAuditRepositoryInterface $productAuditRepository,
    ) {}

    public function execute(User $user, ?string $guestId): int
    {
        $userId    = $user->userId;
        $cartItems = $this->cartRepository->getItems($userId, $guestId);

        if (empty($cartItems)) {
            throw new DomainException('Корзина пуста.');
        }
        $products = Product::with('discount')
            ->whereIn('productId', array_column($cartItems, 'productId'))
            ->get()
            ->keyBy('productId');

        $cartItems = array_map(function ($item) use ($products) {
            $product = $products->get($item['productId'] ?? null);

            if ($product) {
                $item['price'] = Helper::applyDiscount($product, (float) ($item['price'] ?? 0));
            }

            return $item;
        }, $cartItems);

        $address     = $user->address ?? '';
        $totalAmount = array_sum(
            array_map(fn($item) => (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 0), $cartItems)
        );

        DB::beginTransaction();

        try {
            foreach ($cartItems as $item) {
                $productId = (int) ($item['productId'] ?? 0);
                $quantity  = (int) ($item['quantity']  ?? 0);

                if ($productId <= 0 || $quantity <= 0) {
                    throw new \RuntimeException('Некорректные данные товара в корзине.');
                }

                if (! $this->productAuditRepository->decrementStock($productId, $quantity)) {
                    throw new \RuntimeException(
                        'Извините, товар "' . ($item['name'] ?? 'товар') . '" закончился'
                    );
                }
            }

            $orderId = $this->orderRepository->saveOrder($userId, $totalAmount, $address);
            $this->orderRepository->saveOrderItems($orderId, $cartItems);
            $this->cartRepository->clear($userId, $guestId);

            DB::commit();

            return $orderId;

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            throw new DomainException('Не удалось оформить заказ.');
        }
    }
}
