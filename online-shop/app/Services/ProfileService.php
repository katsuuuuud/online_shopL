<?php

namespace App\Services;

use App\Contracts\OrderRepositoryInterface;
use App\Exceptions\DomainException;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ProfileService
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
    ) {}

    public function updateProfile(ProfileUpdateDTO $dto, User $user): void
    {
        if (! $dto->isValid()) {
            throw new DomainException('Все поля профиля должны быть заполнены.');
        }

        try {
            $user->name    = $dto->name;
            $user->phone   = $dto->phone;
            $user->address = $dto->address;
            $user->save();
        } catch (\Throwable $e) {
            throw new DomainException('Ошибка обновления профиля: ' . $e->getMessage());
        }
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

class ProfileDataDTO
{
    public function __construct(
        public readonly Collection $orders,
    ) {}
}
