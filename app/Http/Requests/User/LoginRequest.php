<?php

namespace App\Http\Requests\User;

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
            // メールアドレスのバリデーションルール（必須、メール形式）
            'email' => 'required|email',
            // パスワードのバリデーションルール（必須、string型、最小文字数8文字以上、半角数字・英字大文字・小文字・記号を許可）
            'password' => 'required|string|min:8|regex:/^[a-zA-Z0-9!@#$%^&*]+$/',
            // 次回ログイン省略のバリデーションルール（boolean型）
            'remember' => 'boolean',
        ];
    }
}
