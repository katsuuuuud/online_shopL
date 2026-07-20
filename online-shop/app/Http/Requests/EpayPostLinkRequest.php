<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

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
            'accountId'         => ['sometimes', 'nullable', 'string'],
            'amount'            => ['sometimes', 'nullable', 'numeric'],
            'amount_bonus'      => ['sometimes', 'nullable', 'numeric'],
            'approvalCode'      => ['sometimes', 'nullable', 'string'],
            'cardId'            => ['sometimes', 'nullable', 'string'],
            'cardMask'          => ['sometimes', 'nullable', 'string'],
            'cardType'          => ['sometimes', 'nullable', 'string'],
            'currency'          => ['sometimes', 'nullable', 'string'],
            'dateTime'          => ['sometimes', 'nullable', 'string'],
            'description'       => ['sometimes', 'nullable', 'string'],
            'email'             => ['sometimes', 'nullable', 'string'],
            'id'                => ['sometimes', 'nullable', 'string'],
            'ip'                => ['sometimes', 'nullable', 'string'],
            'ipCity'            => ['sometimes', 'nullable', 'string'],
            'ipCountry'         => ['sometimes', 'nullable', 'string'],
            'ipDistrict'        => ['sometimes', 'nullable', 'string'],
            'ipLatitude'        => ['sometimes', 'nullable', 'numeric'],
            'ipLongitude'       => ['sometimes', 'nullable', 'numeric'],
            'ipRegion'          => ['sometimes', 'nullable', 'string'],
            'issuer'            => ['sometimes', 'nullable', 'string'],
            'issuerBankCountry' => ['sometimes', 'nullable', 'string'],
            'language'          => ['sometimes', 'nullable', 'string'],
            'name'              => ['sometimes', 'nullable', 'string'],
            'phone'             => ['sometimes', 'nullable', 'string'],
            'reason'            => ['sometimes', 'nullable', 'string'],
            'reasonCode'        => ['sometimes', 'nullable', 'integer'],
            'reference'         => ['sometimes', 'nullable', 'string'],
            'secure'            => ['sometimes', 'nullable', 'string'],
            'secureDetails'     => ['sometimes', 'nullable'],
            'terminal'          => ['sometimes', 'nullable', 'string'],
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

    protected function failedValidation(Validator $validator): void
    {
        Log::warning('Epay postLink: неверная структура запроса', $this->all());

        throw new HttpResponseException(
            response()->json(['error' => 'Некорректные данные'], 400)
        );
    }
}
