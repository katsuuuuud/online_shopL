<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Helper
{
    public static function resolveIds(Request $request): array
    {
        $userId  = Auth::id();
        $guestId = $request->cookie('guest_cart_id');

        if (! $userId && ! $guestId) {
            $guestId = (string) Str::uuid();
        }

        return [$userId, $guestId];
    }

    public static function calcTotal(array $items): float
    {
        if (empty($items)) {
            return 0.0;
        }

        $productIds = array_column($items, 'productId');

        $products = Product::with('discount')
            ->whereIn('productId', $productIds)
            ->get()
            ->keyBy('productId');

        return array_sum(array_map(function ($item) use ($products) {
            $price    = (float) ($item['price'] ?? 0);
            $quantity = (int) ($item['quantity'] ?? 0);
            $product  = $products->get($item['productId'] ?? null);

            $finalPrice = $product ? self::applyDiscount($product, $price) : $price;

            return $finalPrice * $quantity;
        }, $items));
    }

    public static function isDiscountActive(Product $product): bool {
        if (! $product->discount_id) {
            return false;
        }

        $discount = $product->discount;

        return (bool) ($discount && $discount->is_active);
    }

    public static function applyDiscount(Product $product, float $price): float
    {
        if (! self::isDiscountActive($product)) {
            return $price;
        }

        $discount = $product->discount;

        $final = match ($discount->type) {
            'percentage'   => $price * (1 - ((float) $discount->discount_value) / 100),
            'fixed_amount' => $price - (float) $discount->discount_value,
            default        => $price,
        };

        return round(max(0.0, $final), 2);
    }

    public static function priceInfo(Product $product, ?float $price): array
    {
        if ($price === null) {
            return [
                'price'          => null,
                'original_price' => null,
                'has_discount'   => false,
            ];
        }

        $active = self::isDiscountActive($product);
        $final  = $active ? self::applyDiscount($product, $price) : $price;

        return [
            'price'          => $final,
            'original_price' => $active ? $price : null,
            'has_discount'   => $active,
        ];
    }
}
