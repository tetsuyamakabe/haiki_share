<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            // 新しいパスワードのバリデーションルール（必須、string型、最小文字数8文字以上）
            'newPassword' => ['required', 'string', 'min:8'],
            // 新しいパスワード（再入力）のバリデーションルール（必須、string型、最小文字数8文字以上、newPasswordと同じか））
            'password_confirmation' => ['required', 'string', 'min:8', 'same:newPassword'],
        ];
    }
}