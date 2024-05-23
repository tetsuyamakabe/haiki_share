<?php

namespace App\Http\Requests\Convenience;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
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
        ];
    }
}
