<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Contracts\FavoritesRepositoryInterface;
use App\Http\Requests\StorePostRequest;
use App\Services\CatalogService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function __construct(
        private CatalogService $catalogService,
        private FavoritesRepositoryInterface $favoritesRepo,
    ) {}

    private function getFavoriteIds(Request $request): array
    {
        $user = $request->user();

        return $user ? $this->favoritesRepo->getProductIds($user->userId) : [];
    }

    public function showProducts(Request $request): View
    {
        $catalogData = $this->catalogService->getProductsForCatalog(null);

        return view('catalog', [
            'categories'       => $catalogData['categories'],
            'products'         => $catalogData['products'],
            'activeCategoryId' => $catalogData['activeCategoryId'],
            'favoriteIds'      => $this->getFavoriteIds($request),
        ]);
    }

    public function showProductsByCategory(StorePostRequest $request, int $categoryId): View
    {
        $catalogData = $this->catalogService->getProductsForCatalog($categoryId);

        return view('catalog', [
            'categories'       => $catalogData['categories'],
            'products'         => $catalogData['products'],
            'activeCategoryId' => $catalogData['activeCategoryId'],
            'favoriteIds'      => $this->getFavoriteIds($request),
        ]);
    }
}
