<?php

namespace App\Http\Requests\Convenience;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            // メールアドレスのバリデーションルール（必須、string型、メール形式、最大文字数255文字以下）
            'email' => 'required|string|email|max:255',
            // パスワードのバリデーションルール（必須、string型、最小文字数8文字以上）
            'password' => 'required|string|min:8',
            // 次回ログイン省略のバリデーションルール（boolean型）
            'remember' => 'boolean',
        ];
    }
}