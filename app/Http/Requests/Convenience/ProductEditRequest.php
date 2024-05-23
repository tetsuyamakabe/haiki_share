<?php

namespace App\Http\Requests\Convenience;

use Illuminate\Foundation\Http\FormRequest;

class ProductEditRequest extends FormRequest
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
            'category_id' => 'required',
            'expiration_date' => 'required|date',
        ];

        // 商品画像がアップロードされた場合のみバリデーションルールを適用
        if ($this->hasFile('product_picture')) {
            $rules['product_picture'] = 'required|file|mimes:jpeg,png,jpg,gif|max:2048';
        }
        return $rules;
    }
}
