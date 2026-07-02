<?php

namespace App\Http\Controllers;

use App\Actions\CreateOrderAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class OrderController extends Controller
{
    public function __construct(private CreateOrderAction $createOrderAction) {}

    public function apiCreate(Request $request): JsonResponse
    {
        $guestId = $request->cookie('guest_cart_id');
        $result  = $this->createOrderAction->execute(Auth::user(), $guestId);

        if (! $result['success']) {
            return response()->json(['error' => $result['message']], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json(['data' => ['orderId' => $result['orderId']]], ResponseAlias::HTTP_CREATED);
    }
}
