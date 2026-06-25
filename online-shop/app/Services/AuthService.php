<?php

namespace App\Services;

use App\Contracts\CartRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(
        private CartRepositoryInterface $cartRepository
    ) {}

    public function login(array $data, ?string $guestId): array
    {
        $email    = trim((string) ($data['email']    ?? ''));
        $password = trim((string) ($data['password'] ?? ''));
        $next     = trim((string) ($data['next']     ?? '/'));

        if ($email === '' || $password === '') {
            return ['success' => false, 'message' => 'Введите email и пароль.', 'next' => $next];
        }

        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            return ['success' => false, 'message' => 'Неверный email или пароль.', 'next' => $next];
        }

        Auth::login($user);

        $this->cartRepository->mergeGuestCartToUser($user->userId, $guestId);

        return ['success' => true, 'message' => 'Вы успешно вошли.', 'next' => $next, 'user' => $user];
    }

    public function register(array $data, ?string $guestId): array
    {
        $name     = trim((string) ($data['name']     ?? ''));
        $email    = trim((string) ($data['email']    ?? ''));
        $phone    = trim((string) ($data['phone']    ?? ''));
        $address  = trim((string) ($data['address']  ?? ''));
        $password = trim((string) ($data['password'] ?? ''));
        $next     = trim((string) ($data['next']     ?? '/'));

        if ($name === '' || $email === '' || $phone === '' || $address === '' || $password === '') {
            return ['success' => false, 'message' => 'Заполните все поля для регистрации.', 'next' => $next];
        }

        if (User::where('email', $email)->exists()) {
            return ['success' => false, 'message' => 'Пользователь с таким email уже зарегистрирован.', 'next' => $next];
        }

        $user = User::create([
            'name'     => $name,
            'email'    => $email,
            'phone'    => $phone,
            'address'  => $address,
            'password' => Hash::make($password),
        ]);

        Auth::login($user);

        $this->cartRepository->mergeGuestCartToUser($user->userId, $guestId);

        return ['success' => true, 'message' => 'Регистрация прошла успешно.', 'next' => $next, 'user' => $user];
    }

    public function logout(): void
    {
        Auth::logout();
    }
}
