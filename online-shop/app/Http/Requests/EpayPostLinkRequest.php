<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EpayPostLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoiceId'   => ['required', 'string'],
            'secret_hash' => ['required', 'string'],
            'code'        => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'invoiceId.required'   => 'invoiceId отсутствует.',
            'secret_hash.required' => 'secret_hash отсутствует.',
            'code.required'        => 'code отсутствует.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        \Illuminate\Support\Facades\Log::warning('Epay postLink: неверная структура запроса', $this->all());

        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            response()->json(['error' => 'Некорректные данные'], 400)
        );
    }
}
