<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            // メールアドレスのバリデーションルール（必須、string型、メール形式、最大文字数255文字以下）
            'email' => 'required|string|email|max:255',
            // お問い合わせ内容のバリデーションルール（必須、string型、最大文字数255文字以下）
            'contact' => 'required|string|max:255',
        ];
    }
}
