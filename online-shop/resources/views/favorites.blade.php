@extends('app')

@section('title', 'Избранное')
@section('header-meta', 'ИЗБРАННОЕ')

@section('content')
    <div class="wrapper">
        <main>
            <a href="/" class="back-home">←</a>

            <div class="section-head">
                <h1>Избранное</h1>
                <span class="count">{{ count($items) }} шт.</span>
            </div>

            @if(empty($items))
                <p>В избранном пока ничего нет.</p>
            @else
                <div class="grid">
                    @foreach($items as $product)
                        @php
                            $productId     = $product['productId'];
                            $name          = $product['name'];
                            $categoryName  = $product['category_name'] ?? '—';
                            $hasDiscount   = $product['has_discount'] ?? false;
                            $price         = $product['price'] ?? null;
                            $originalPrice = $product['original_price'] ?? null;
                            $currency      = $product['currency'] ?? null;
                            $inStock       = $product['in_stock'] ?? true;
                        @endphp
                        <div class="card" data-product-id="{{ $productId }}">
                            @if($hasDiscount)
                                <span class="tag tag-sale">SALE</span>
                            @endif
                            @if(!$inStock)
                                <span class="tag tag-out-of-stock">нет на складе</span>
                            @endif

                            <div class="card-img"></div>

                            <div>
                                <div class="card-name">{{ $name }}</div>
                                <div class="card-category">{{ $categoryName }}</div>
                            </div>

                            <div class="card-footer">
                                <span class="price">
                                    @if($price)
                                        @if($hasDiscount && $originalPrice)
                                            <span class="price-old">{{ number_format($originalPrice, 2) }} {{ $currency }}</span>
                                        @endif
                                        {{ number_format($price, 2) }} {{ $currency }}
                                    @else
                                        —
                                    @endif
                                </span>
                                <button type="button" class="btn remove-from-favorites"
                                        data-product-id="{{ $productId }}">Убрать</button>
                                @if($inStock)
                                    <button type="button" class="btn add-to-cart"
                                            data-product-id="{{ $productId }}">+</button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </main>
    </div>
@endsection
