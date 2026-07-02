<?php

namespace App\Helpers;

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
        return array_sum(array_map(
            fn($i) => (float) ($i['price'] ?? 0) * (int) ($i['quantity'] ?? 0),
            $items
        ));
    }
}
