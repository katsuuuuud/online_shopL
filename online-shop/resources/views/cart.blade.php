@extends('app')

@section('title', 'Корзина')
@section('header-meta', auth()->check() ? 'Привет, ' . auth()->user()->name : 'КОРЗИНА')

@section('content')
    <div class="wrapper">
        <main>
            <a href="/" class="back-home">←</a>

            <div class="section-head">
                <h1>Корзина</h1>
            </div>

            @if(empty($items))
                <p>Корзина пуста.</p>
            @else
                <div class="cart-content">
                    <ul class="cart-list">
                        @foreach($items as $item)
                            <li class="cart-item">
                                <span>{{ $item['name'] }} — {{ (int)$item['quantity'] }} шт.</span>
                                <button type="button" class="btn-cart remove-from-cart"
                                        data-product-id="{{ (int)$item['productId'] }}">Удалить</button>
                            </li>
                        @endforeach
                    </ul>

                    <div class="cart-actions">
                        <button type="button" class="btn-cart clear-cart">Очистить корзину</button>
                        @auth
                            <button type="button" class="btn-cart make-order">Оформить заказ</button>
                        @else
                            <a class="btn-cart" href="/auth/login?next=/cart">Войти для оформления</a>
                        @endauth
                    </div>
                </div>

                @auth
                    <div class="cart-modal order-form-modal">
                        <div class="cart-modal-inner">
                            <h2>Оформление заказа</h2>
                            <form id="order-form">
                                @csrf
                                <button type="submit" class="btn-cart">Подтвердить заказ</button>
                            </form>
                        </div>
                    </div>
                @endauth
            @endif
        </main>
    </div>
@endsection
