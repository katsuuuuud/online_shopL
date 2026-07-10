<?php
declare(strict_types=1);
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'categoryId' => $this->route('categoryId'),
        ]);
    }

    public function rules(): array
    {
        return [
            'categoryId' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'categoryId.required' => 'Категория не указана.',
            'categoryId.integer'  => 'Идентификатор категории должен быть числом.',
            'categoryId.min'      => 'Идентификатор категории должен быть больше 0.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        abort(404);
    }
}
