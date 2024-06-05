<?php

namespace App\Http\Requests\Convenience;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
            'maxprice' => ['nullable', 'numeric', 'min:0'], // 最大価格のバリデーションルール
            'minprice' => ['nullable', 'numeric', 'min:0'], // 最小価格のバリデーションルール
        ];
    }
}
