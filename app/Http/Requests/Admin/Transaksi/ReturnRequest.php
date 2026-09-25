<?php

namespace App\Http\Requests\Admin\Transaksi;

use Illuminate\Foundation\Http\FormRequest;

class ReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items'   => ['required', 'array'],
            'items.*' => ['nullable', 'integer', 'min:0'],
            'reason'  => ['required', 'string', 'max:255'],
        ];
    }
}
