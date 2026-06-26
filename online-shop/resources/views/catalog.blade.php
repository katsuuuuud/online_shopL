@extends('app')

@section('title', 'Каталог')
@section('header-meta', 'КАТАЛОГ')

@section('content')
    <div class="wrapper">
        <aside>
            <p class="sidebar-title">Категории</p>
            <ul class="cat-list">
                <li>
                    <a href="/" class="{{ $activeCategoryId === null ? 'active' : '' }}">
                        <span class="cat-dot"></span>Все товары
                    </a>
                </li>
                @foreach($categories as $cat)
                    <li>
                        <a href="/?category={{ $cat['categoryId'] ?? $cat->categoryId }}"
                           class="{{ $activeCategoryId === ($cat['categoryId'] ?? $cat->categoryId) ? 'active' : '' }}">
                            <span class="cat-dot"></span>
                            {{ $cat['name'] ?? $cat->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>

        <main>
            <div class="section-head">
                <h1>Все товары</h1>
                <span class="count">{{ count($products) }} шт.</span>
            </div>

            <div class="grid">
                @foreach($products as $product)
                    @php
                        $productId   = $product['productId']   ?? $product->productId;
                        $name        = $product['name'];
                        $categoryName= $product['category_name'] ?? '—';
                        $hasDiscount = $product['has_discount'] ?? false;
                        $price       = $product['price']       ?? null;
                        $currency    = $product['currency']    ?? null;
                    @endphp
                    <div class="card">
                        @if($hasDiscount)
                            <span class="tag tag-sale">SALE</span>
                        @endif

                        <div class="card-img"></div>

                        <div>
                            <div class="card-name">{{ $name }}</div>
                            <div class="card-category">{{ $categoryName }}</div>
                        </div>

                        <div class="card-footer">
                        <span class="price">
                            @if($price)
                                {{ number_format($price, 2) }} {{ $currency }}
                            @else
                                —
                            @endif
                        </span>
                            <button type="button" class="btn-cart add-to-cart"
                                    data-product-id="{{ $productId }}">+</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </main>
    </div>
@endsection
