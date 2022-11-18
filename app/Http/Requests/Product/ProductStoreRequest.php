<?php

namespace App\Http\Requests\Product;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductStoreRequest extends FormRequest
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
            'productName' => 'required|max:255',
            'amountAvailable' => 'required|integer',
            'cost' => 'required|numeric',
            'sellerId' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    return $query->where('role', Role::SELLER);
                })
            ],
        ];
    }
}
