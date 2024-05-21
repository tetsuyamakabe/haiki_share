<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            // メールアドレスのバリデーションルール（必須、string型、メール形式、最大文字数255文字以下、ユニーク制約）
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // パスワードのバリデーションルール（必須、string型、最小文字数8文字以上、パスワード再入力と同じ値か）
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // ロールのバリデーションルール（必須、string型、userかどうか）
            'role' => ['required', 'string', 'in:user'],
            // 利用規約のバリデーションルール（必須、規約に同意しているか）
            'agreement' => ['required', 'accepted'],
        ];
    }
}
