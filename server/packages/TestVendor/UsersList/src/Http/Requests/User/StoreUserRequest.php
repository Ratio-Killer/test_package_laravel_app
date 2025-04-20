<?php

namespace TestVendor\UsersList\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'        => ['required', 'string', 'max:255'],
            'last_name'         => ['required', 'string', 'max:255'],
            'phones'            => ['required', 'array', 'min:1'],
            'phones.*'          => ['required', 'string', 'regex:/^[+0-9\s\-().]{7,20}$/', 'distinct', 'unique:phones,number'],
        ];
    }
}
