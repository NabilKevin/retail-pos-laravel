<?php

namespace App\Http\Requests\Kasir\Pos;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cart'               => ['required', 'array', 'min:1'],
            'cart.*.id'          => ['required', 'integer', 'exists:obat,id'],
            'cart.*.qty'         => ['required', 'integer', 'min:1'],
            'totalTransaction'   => ['required', 'integer', 'min:1'],
            'totalPaid'          => ['required', 'integer', 'min:1', 'gte:totalTransaction'],
            'totalChange'        => ['required', 'integer', 'min:0'],
        ];
    }
}
