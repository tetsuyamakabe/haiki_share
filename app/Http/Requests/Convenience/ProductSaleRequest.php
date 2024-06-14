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
            // 名前のバリデーションルール（必須、string型、最大文字数255文字以下）
            'name' => 'required|string|max:255',
            // 価格のバリデーションルール（必須、数値、最小値0以上）
            'price' => 'required|numeric|min:0',
            // カテゴリのバリデーションルール（必須）
            'category' => 'required',
            // 賞味期限のバリデーションルール（必須、date型）
            'expiration_date' => 'required|date',
            // 商品画像のバリデーションルール（必須、画像ファイル、拡張子、最大画像ファイルサイズ2MB以下）
            'product_picture' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
