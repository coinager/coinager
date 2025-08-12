<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncomeStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'person_id' => ['nullable', 'exists:people,id'],
            'account_id' => ['required', 'exists:accounts,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string', 'max:255'],
            'transacted_at' => ['sometimes', 'required', 'date'],
            'amount' => ['required', 'numeric'],
        ];
    }
}
