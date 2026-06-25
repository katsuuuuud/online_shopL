@extends('app')

@section('title', 'Авторизация')
@section('body-class', 'auth-page')
@section('header-meta', 'АВТОРИЗАЦИЯ')

@section('content')
    <main>
        <div class="auth-box">
            <a href="/" class="back-home">←</a>

            <h1>Авторизация</h1>

            @if($error)
                <div class="alert alert-error">{{ $error }}</div>
            @endif

            <div class="auth-tabs">
                <a href="/auth/login{{ $next !== '/' ? '?next=' . urlencode($next) : '' }}"
                   class="auth-tab {{ $mode === 'login' ? 'auth-tab--active' : 'auth-tab--idle' }}">Вход</a>
                <a href="/auth/register{{ $next !== '/' ? '?next=' . urlencode($next) : '' }}"
                   class="auth-tab {{ $mode === 'register' ? 'auth-tab--active' : 'auth-tab--idle' }}">Регистрация</a>
            </div>

            @if($mode === 'login')
                <div class="auth-section">
                    <h2>Вход</h2>
                    <form action="/auth/login" method="post" class="auth-form" data-mode="login">
                        @csrf
                        <input type="hidden" name="next" value="{{ $next }}">
                        <input type="email"    name="email"    placeholder="Email"  required>
                        <input type="password" name="password" placeholder="Пароль" required>
                        <button type="submit" class="btn-cart">Войти</button>
                    </form>
                </div>
            @else
                <div class="auth-section">
                    <h2>Регистрация</h2>
                    <form action="/auth/register" method="post" class="auth-form" data-mode="register">
                        @csrf
                        <input type="hidden"   name="next"     value="{{ $next }}">
                        <input type="text"     name="name"     placeholder="Имя"     required>
                        <input type="email"    name="email"    placeholder="Email"   required>
                        <input type="text"     name="phone"    placeholder="Телефон" required>
                        <input type="text"     name="address"  placeholder="Адрес"   required>
                        <input type="password" name="password" placeholder="Пароль"  required>
                        <button type="submit" class="btn-cart">Зарегистрироваться</button>
                    </form>
                </div>
            @endif
        </div>
    </main>
@endsection
