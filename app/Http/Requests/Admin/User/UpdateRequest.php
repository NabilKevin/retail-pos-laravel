<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'nama'     => ['sometimes', 'string', 'max:100'],
            'username' => ['sometimes', 'string', 'max:50', "unique:user,username,{$id}"],
            'password' => ['nullable', 'string', 'min:8', 'max:255', 'confirmed'],
            'role'     => ['sometimes', 'in:admin,kasir'],
        ];
    }
}