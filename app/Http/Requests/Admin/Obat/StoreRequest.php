<?php

namespace App\Http\Requests\Admin\Obat;

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
            'kode_barcode' => ['required', 'string', 'size:13', 'unique:obat,kode_barcode'],
            'nama'         => ['required', 'string', 'max:255'],
            'stok'         => ['required', 'integer', 'min:0'],
            'tipe_id'      => ['required', 'integer', 'exists:tipeobat,id'],
            'harga_modal'  => ['required', 'integer', 'min:0'],
            'harga_jual'   => ['required', 'integer', 'min:0'],
            'expired_at'   => ['required', 'date'],
        ];
    }
}
