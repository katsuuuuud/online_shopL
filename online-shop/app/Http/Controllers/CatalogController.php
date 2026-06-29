<?php

namespace App\Http\Controllers;

use App\Services\CatalogService;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function __construct(private CatalogService $catalogService) {}

    public function showProducts(?int $categoryId = null)
    {
        $catalogData = $this->catalogService->getProductsForCatalog($categoryId);

        return view('catalog', [
            'categories'       => $catalogData['categories'],
            'products'         => $catalogData['products'],
            'activeCategoryId' => $catalogData['activeCategoryId'],
        ]);
    }
}
