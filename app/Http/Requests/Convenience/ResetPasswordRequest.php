<?php

namespace App\Http\Requests\Convenience;

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
            // 新しいパスワードのバリデーションルール（必須、string型、最小文字数8文字以上、半角数字・英字大文字・小文字・記号を許可）
            'newPassword' => ['required', 'string', 'min:8', 'regex:/^[a-zA-Z0-9!@#$%^&*]+$/'],
            // 新しいパスワード（再入力）のバリデーションルール（必須、string型、最小文字数8文字以上、newPasswordと同じか、半角数字・英字大文字・小文字・記号を許可）
            'password_confirmation' => ['required', 'string', 'min:8', 'same:newPassword', 'regex:/^[a-zA-Z0-9!@#$%^&*]+$/'],
        ];
    }
}
