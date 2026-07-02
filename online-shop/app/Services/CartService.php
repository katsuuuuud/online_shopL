<?php

namespace App\Services;

use App\Contracts\CartRepositoryInterface;
use App\Contracts\CatalogRepositoryInterface;
use App\Exceptions\DomainException;
use App\Helpers\Helper;
use Illuminate\Http\Request;

class CartService
{
    public function __construct(
        private CartRepositoryInterface    $cartRepo,
        private CatalogRepositoryInterface $catalogRepo,
    ) {}

    public function getItems(Request $request): array
    {
        [$userId, $guestId] = Helper::resolveIds($request);
        $items = $this->cartRepo->getItems($userId, $guestId);

        return [
            'items'   => array_values($items),
            'total'   => Helper::calcTotal($items),
            'guestId' => $guestId,
            'userId'  => $userId,
        ];
    }

    public function addItem(Request $request, int $productId, int $quantity): array
    {
        $product = $this->catalogRepo->getProductById($productId);
        if (! $product) {
            throw new DomainException('Товар не найден', 404);
        }

        $priceData = $this->catalogRepo->getActivePrice($productId);
        $price     = $priceData ? (float) $priceData->price : 0.0;
        $currency  = $priceData ? $priceData->currency       : 'USD';

        [$userId, $guestId] = Helper::resolveIds($request);

        $items = $this->cartRepo->addItem(
            $userId, $guestId,
            $productId, $product->name, $price, $currency, $quantity
        );

        return [
            'items'   => array_values($items),
            'total'   => Helper::calcTotal($items),
            'guestId' => $guestId,
            'userId'  => $userId,
        ];
    }

    public function removeItem(Request $request, int $productId): array
    {
        [$userId, $guestId] = Helper::resolveIds($request);
        $items = $this->cartRepo->removeItem($userId, $guestId, $productId);

        return [
            'items' => array_values($items),
            'total' => Helper::calcTotal($items),
        ];
    }

    public function clear(Request $request): void
    {
        [$userId, $guestId] = Helper::resolveIds($request);
        $this->cartRepo->clear($userId, $guestId);
    }
}
