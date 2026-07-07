<?php

namespace App\Services;

use App\Contracts\CartRepositoryInterface;
use App\Exceptions\DomainException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(
        private CartRepositoryInterface $cartRepository
    ) {}

    public function login(LoginDTO $dto, ?string $guestId): User
    {
        $user = User::where('email', $dto->email)->first();

        if (! $user || ! Hash::check($dto->password, $user->password)) {
            throw new DomainException('Неверный email или пароль.', 401);
        }

        Auth::login($user);

        $this->cartRepository->mergeGuestCartToUser($user->userId, $guestId);

        return $user;
    }

    public function register(RegisterDTO $dto, ?string $guestId): User
    {
        if (User::where('email', $dto->email)->exists()) {
            throw new DomainException('Пользователь с таким email уже зарегистрирован.', 422);
        }

        $user = User::create([
            'name'     => $dto->name,
            'email'    => $dto->email,
            'phone'    => $dto->phone,
            'address'  => $dto->address,
            'password' => Hash::make($dto->password),
        ]);

        Auth::login($user);

        $this->cartRepository->mergeGuestCartToUser($user->userId, $guestId);

        return $user;
    }

    public function logout(): void
    {
        Auth::logout();
    }
}

class LoginDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            email: trim((string) ($data['email'] ?? '')),
            password: trim((string) ($data['password'] ?? '')),
        );
    }

}

class RegisterDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $phone,
        public readonly string $address,
        public readonly string $password,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: trim((string) ($data['name'] ?? '')),
            email: trim((string) ($data['email'] ?? '')),
            phone: trim((string) ($data['phone'] ?? '')),
            address: trim((string) ($data['address'] ?? '')),
            password: trim((string) ($data['password'] ?? '')),
        );
    }

}
