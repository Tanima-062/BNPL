<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'merchant_id' => ['required', 'exists:merchants,id'],
            'total_amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'string', 'max:10'],
            'installments_count' => ['required', 'integer', 'min:1', 'max:24'],
        ];
    }
}