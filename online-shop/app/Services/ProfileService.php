<?php

namespace App\Services;

use App\Contracts\OrderRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ProfileService
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
    ) {}

    public function updateProfile(ProfileUpdateDTO $dto, User $user): ProfileUpdateResultDTO
    {
        if (! $dto->isValid()) {
            return ProfileUpdateResultDTO::failure('Все поля профиля должны быть заполнены.');
        }

        try {
            $user->name    = $dto->name;
            $user->phone   = $dto->phone;
            $user->address = $dto->address;
            $user->save();
        } catch (\Throwable $e) {
            return ProfileUpdateResultDTO::failure('Ошибка обновления профиля: ' . $e->getMessage());
        }

        return ProfileUpdateResultDTO::success('Профиль успешно обновлён.');
    }

    public function getProfileData(User $user): ProfileDataDTO
    {
        return new ProfileDataDTO(
            orders: $this->orderRepository->getOrdersByCustomer($user->userId),
        );
    }
}

class ProfileUpdateDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $phone,
        public readonly string $address,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: trim((string) ($data['name'] ?? '')),
            phone: trim((string) ($data['phone'] ?? '')),
            address: trim((string) ($data['address'] ?? '')),
        );
    }

    public function isValid(): bool
    {
        return $this->name !== '' && $this->phone !== '' && $this->address !== '';
    }
}

class ProfileUpdateResultDTO
{
    private function __construct(
        public readonly bool $success,
        public readonly string $message,
    ) {}

    public static function success(string $message): self
    {
        return new self(success: true, message: $message);
    }

    public static function failure(string $message): self
    {
        return new self(success: false, message: $message);
    }
}

class ProfileDataDTO
{
    public function __construct(
        public readonly Collection $orders,
    ) {}
}
