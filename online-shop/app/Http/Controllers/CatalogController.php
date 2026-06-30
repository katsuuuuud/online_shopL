<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Services\CatalogService;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function __construct(private CatalogService $catalogService) {}

    public function showProducts(Request $request)
    {
        $catalogData = $this->catalogService->getProductsForCatalog(null);

        return view('catalog', [
            'categories'       => $catalogData['categories'],
            'products'         => $catalogData['products'],
            'activeCategoryId' => $catalogData['activeCategoryId'],
        ]);
    }

    public function showProductsByCategory(StorePostRequest $request, int $categoryId)
    {
        $catalogData = $this->catalogService->getProductsForCatalog($categoryId);

        return view('catalog', [
            'categories'       => $catalogData['categories'],
            'products'         => $catalogData['products'],
            'activeCategoryId' => $catalogData['activeCategoryId'],
        ]);
    }
}
