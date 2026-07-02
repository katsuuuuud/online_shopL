<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'productId' => ['required', 'integer', 'min:1'],
            'quantity'  => ['sometimes', 'integer', 'min:1'],        ];
    }

    public function messages(): array
    {
        return [
            'productId.required' => 'Не указан товар.',
            'productId.integer'  => 'Идентификатор товара должен быть числом.',
            'quantity.integer'   => 'Количество должно быть числом.',
            'quantity.min'       => 'Количество должно быть не меньше 1.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        abort(404);
    }
}
