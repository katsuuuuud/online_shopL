<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Services\FavoritesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoritesController extends Controller
{
    public function __construct(private FavoritesService $favoritesService) {}

    public function show(Request $request): View
    {
        $items = $this->favoritesService->getItems($request->user()->userId);

        return view('favorites', [
            'items' => $items,
        ]);
    }

    public function apiAdd(Request $request): JsonResponse
    {
        $productId = (int) $request->input('productId');

        if ($productId <= 0) {
            return response()->json(['error' => 'Неверный productId'], 422);
        }

        $this->favoritesService->add($request->user()->userId, $productId);

        return response()->json(['data' => true], 201);
    }

    public function apiRemove(Request $request, int $productId): JsonResponse
    {
        if ($productId <= 0) {
            return response()->json(['error' => 'Неверный productId'], 422);
        }

        $this->favoritesService->remove($request->user()->userId, $productId);

        return response()->json(['data' => true]);
    }
}
