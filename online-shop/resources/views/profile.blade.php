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
                                @php
                                    $statusLabels = [
                                        'pending payment' => 'Ожидает оплаты',
                                        'failed'  => 'Оплата не прошла',
                                        'paid'    => 'Оплачен',
                                    ];
                                    $statusLabel = $statusLabels[$order->status] ?? $order->status;
                                @endphp
                                <li class="order-card">
                                    <div><strong>Заказ #</strong>{{ $order->orderId }}</div>
                                    <div><strong>Дата:</strong> {{ $order->created_at -> format('d.m.Y')}}</div>
                                    <div><strong>Сумма:</strong> {{ number_format($order->amount, 2) }}</div>
                                    <div><strong>Статус:</strong> <span class="order-status order-status--{{ $order->status }}">{{ $statusLabel }}</span></div>
                                    <div><strong>Адрес:</strong> {{ $order->address }}</div>

                                    @if(in_array($order->status, ['pending payment', 'failed']))
                                        <button type="button" class="btn pay-order" data-order-id="{{ $order->orderId }}">
                                            {{ $order->status === 'failed' ? 'Оплатить повторно' : 'Оплатить' }}
                                        </button>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @else
                    <h2>Профиль</h2>
                    <form class="profile-form" data-url="{{ $urlUpdate }}">
                        @csrf
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
                        <button type="submit" class="btn">Сохранить</button>
                    </form>
                @endif
            </section>
        </div>
    </main>
@endsection

@section('scripts')
    <script src="{{ config('epay.payform_js_url') }}"></script>
@endsection
