<?php

namespace App\Services;

use App\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class ProfileService
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
    ) {}

    public function updateProfile(array $data, object $user): array
    {
        $name    = trim((string) ($data['name']    ?? ''));
        $phone   = trim((string) ($data['phone']   ?? ''));
        $address = trim((string) ($data['address'] ?? ''));

        if ($name === '' || $phone === '' || $address === '') {
            return ['success' => false, 'message' => 'Все поля профиля должны быть заполнены.'];
        }

        try {
            $user->name    = $name;
            $user->phone   = $phone;
            $user->address = $address;
            $user->save();
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'Ошибка обновления профиля: ' . $e->getMessage()];
        }

        return ['success' => true, 'message' => 'Профиль успешно обновлён.'];
    }

    public function getProfileData(object $user): array
    {
        return [
            'orders' => $this->orderRepository->getOrdersByCustomer($user->userId),
        ];
    }
}
