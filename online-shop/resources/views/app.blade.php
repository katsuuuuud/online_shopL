<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SHOP')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@700&family=Mulish:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="@yield('body-class')">

<header>
    <a class="logo" href="/">SHOP<span>.</span></a>
    <span class="header-meta">@yield('header-meta', 'КАТАЛОГ')</span>

    @auth
        <a class="btn" href="{{ url('cart') }}">Корзина</a>
        <a class="btn" href="{{ url('profile') }}">Кабинет</a>
        <a class="btn" href="{{ url('auth/logout') }}">Выйти</a>
    @else
        <a class="btn" href="{{ url('cart') }}">Корзина</a>
        <a class="btn" href="{{ url('auth/login') }}">Войти</a>
    @endauth
</header>

@yield('content')

<footer>© 2026 Shop</footer>

<script src="{{ asset('js/main.js') }}"></script>
@yield('scripts')
</body>
</html>
