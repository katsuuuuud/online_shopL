@extends('app')

@section('title', 'Личный кабинет')
@section('body-class', 'profile-page')
@section('header-meta', 'ЛИЧНЫЙ КАБИНЕТ')

@section('content')
    <main>
        <a href="/" class="back-home">←</a>

        <div class="profile-layout">
            <aside class="profile-nav">
                <a href="/profile?tab=info"
                   class="profile-nav-link {{ $tab === 'info'   ? 'profile-nav-link--active' : 'profile-nav-link--idle' }}">Профиль</a>
                <a href="/profile?tab=orders"
                   class="profile-nav-link {{ $tab === 'orders' ? 'profile-nav-link--active' : 'profile-nav-link--idle' }}">Мои заказы</a>
            </aside>

            <section class="profile-section">
                @if($error)
                    <div class="alert alert-error">{{ $error }}</div>
                @endif
                @if($success)
                    <div class="alert alert-success">{{ $success }}</div>
                @endif

                @if($tab === 'orders')
                    <h2>Мои заказы</h2>
                    @if($orders->isEmpty())
                        <p>Пока нет заказов.</p>
                    @else
                        <ul class="orders-list">
                            @foreach($orders as $order)
                                <li class="order-card">
                                    <div><strong>Заказ #</strong>{{ $order->orderId }}</div>
                                    <div><strong>Дата:</strong> {{ $order->created_at }}</div>
                                    <div><strong>Сумма:</strong> {{ number_format($order->amount, 2) }}</div>
                                    <div><strong>Статус:</strong> {{ $order->status }}</div>
                                    <div><strong>Адрес:</strong> {{ $order->address }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @else
                    <h2>Профиль</h2>
                    <form action="/profile" method="post" class="profile-form">
                        @csrf
                        <input type="hidden" name="tab" value="info">
                        <label>
                            Имя
                            <input type="text" name="name" value="{{ $user->name }}" required>
                        </label>
                        <label>
                            Email
                            <input type="email" value="{{ $user->email }}" disabled>
                        </label>
                        <label>
                            Телефон
                            <input type="text" name="phone" value="{{ $user->phone }}" required>
                        </label>
                        <label>
                            Адрес
                            <input type="text" name="address" value="{{ $user->address }}" required>
                        </label>
                        <button type="submit" class="btn-cart">Сохранить</button>
                    </form>
                @endif
            </section>
        </div>
    </main>
@endsection
