<?php

namespace App\Http\Requests\Convenience;

use Illuminate\Foundation\Http\FormRequest;

class ProductSaleRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required',
            'expiration_date' => 'required|date',
            'product_picture.*' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
