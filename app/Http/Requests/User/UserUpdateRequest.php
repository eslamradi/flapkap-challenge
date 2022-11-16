<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'username' => [
                'required',
                'alpha_dash',
                Rule::unique('users', 'username')->ignore($this->route('username'), 'username')
            ],
            'password' => 'nullable|min:8',
            'role' => 'required|in:seller,buyer'
        ];
    }
}
