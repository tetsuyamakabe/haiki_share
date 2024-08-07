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
        $rules = [
            // 名前のバリデーションルール（必須、string型、最大文字数255文字以下）
            'name' => 'required|string|max:255',
            // 価格のバリデーションルール（必須、数値、最小文字数0文字以下）
            'price' => 'required|numeric|min:0',
            // カテゴリのバリデーションルール（必須）
            'category' => 'required',
            // 賞味期限のバリデーションルール（必須、date型）
            'expiration_date' => 'required|date',
        ];

        // 商品画像がアップロードされた場合のみバリデーションルールを適用
        if ($this->hasFile('product_picture')) {
            // 商品画像（配列内のすべての要素に適用）のバリデーションルール（NULL許可、画像ファイル、拡張子、最大画像ファイルサイズ2MB以下）
            $rules['product_picture'] = 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048';
        }
        return $rules;
    }
}